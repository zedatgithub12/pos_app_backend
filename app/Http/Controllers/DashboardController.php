<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\SoldPackage;
use App\Models\Stock;
use App\Models\ShopTarget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function getSalesAgainstTarget(string $name)
    {

        $shop = $name;

        if ($shop !== "All") {
            $target = ShopTarget::where('shopname', $shop)->where("status", "active")->first();

            if ($target) {
                $startDate = $target->created_at;
                $lastThirtyDaysSales = $this->getLastThirtyDaysSales($shop, $startDate);
                $getDailyRevenue = $this->getDailyRevenue($shop);
                $getMonthlyRevenue = $this->getMonthlyRevenue($shop, $startDate);
                $getYearlyRevenue = $this->getYearlyRevenue($shop, $startDate);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'thirtydays' => $lastThirtyDaysSales,
                        'daily' => $getDailyRevenue,
                        'monthly' => $getMonthlyRevenue,
                        'annually' => $getYearlyRevenue,
                        'target' => $target,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "The shop doesn't have active target!",

                ], 404);
            }
        } else {

            $shopTargets = ShopTarget::where("status", "active")->get();

            $r_daily = 0;
            $r_monthly = 0;
            $r_yearly = 0;

            if ($shopTargets) {

                foreach ($shopTargets as $target) {

                    $r_daily += $target->r_daily;
                    $r_monthly += $target->r_monthly;
                    $r_yearly += $target->r_yearly;
                }

                $dailyRevenue = Sale::whereDate('created_at', Carbon::today())->sum('grandTotal');
                $monthlyRevenue = Sale::whereMonth('created_at', Carbon::now()->month)->sum('grandTotal');
                $annualRevenue = Sale::whereYear('created_at', Carbon::now()->year)->sum('grandTotal');
                $lastThirtyDaysSales = $this->getTotalThirtyDaysSales();

                $dailyRevenue = Sale::whereDate('created_at', Carbon::today())->sum('grandTotal');
                $monthlyRevenue = Sale::whereMonth('created_at', Carbon::now()->month)->sum('grandTotal');
                $annualRevenue = Sale::whereYear('created_at', Carbon::now()->year)->sum('grandTotal');

                $dailyPackageRevenue = SoldPackage::whereDate('created_at', Carbon::today())->sum('grandTotal');
                $monthlyPackageRevenue = SoldPackage::whereMonth('created_at', Carbon::now()->month)->sum('grandTotal');
                $annualPackageRevenue = SoldPackage::whereYear('created_at', Carbon::now()->year)->sum('grandTotal');

                $totalRevenue = $dailyRevenue + $dailyPackageRevenue;
                $totalMonthlyRevenue = $monthlyRevenue + $monthlyPackageRevenue;
                $totalAnnualRevenue = $annualRevenue + $annualPackageRevenue;

                return response()->json([
                    'success' => true,
                    'data' => [
                        'thirtydays' => $lastThirtyDaysSales,
                        'daily' => $totalRevenue,
                        'monthly' => $totalMonthlyRevenue,
                        'annually' => $totalAnnualRevenue,
                        'target' => $shopTargets[0],
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "The shop doesn't have active target!",

                ], 404);
            }
        }
    }

    public function getLastThirtyDaysSales($shop, $startDate)
    {
        $startDate = Carbon::parse($startDate)->subDays(30)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $sales = DB::table('sales')
            ->select(DB::raw("DATE(created_at) as date"), DB::raw("SUM(grandtotal) as totalRevenue"))
            ->where('shop', $shop)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('created_at', 'desc')
            ->get();

        $soldPackages = DB::table('sold_packages')
            ->select(DB::raw("DATE(created_at) as date"), DB::raw("SUM(grandtotal) as totalRevenue"))
            ->where('shop', $shop)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('created_at', 'desc')
            ->get();

        $salesArray = [];

        foreach ($sales as $sale) {
            $salesArray[] = [
                'date' => $sale->date,
                'totalRevenue' => $sale->totalRevenue,
                
            ];
        }

        foreach ($soldPackages as $package) {
            if (isset($salesArray[$package->date])) {
                $salesArray[$package->date]['totalRevenue'] += $package->totalRevenue;
            } else {
                $salesArray[$package->date] = [
                    'date' => $package->date,
                    'totalRevenue' => $package->totalRevenue,
                ];
            }
        }

        // return array_values($salesArray);
        // // return $salesArray;

        $data = collect($salesArray)->sortByDesc('date')->values()->toArray();
        return $data;

    }
    public function getTotalThirtyDaysSales()
    {
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $sales = DB::table('sales')
            ->select(DB::raw("DATE(created_at) as date"), DB::raw("SUM(grandtotal) as totalRevenue"))
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('created_at', 'desc')
            ->get();



        $soldPackages = DB::table('sold_packages')
            ->select(DB::raw("DATE(created_at) as date"), DB::raw("SUM(grandtotal) as totalRevenue"))
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('created_at', 'desc')
            ->get();


        $salesArray = [];

        foreach ($sales as $sale) {
            $salesArray[] = [
                'date' => $sale->date,
                'totalRevenue' => $sale->totalRevenue,
            ];
        }

        foreach ($soldPackages as $package) {
            if (isset($salesArray[$package->date])) {
                $salesArray[$package->date]['totalRevenue'] += $package->totalRevenue;
            } else {
                $salesArray[$package->date] = [
                    'date' => $package->date,
                    'totalRevenue' => $package->totalRevenue,
                ];
            }
        }

        $data = collect($salesArray)->sortByDesc('date')->values()->toArray();
        return $data;
        // return $salesArray;
    }
    public function getMonthlyTargets(string $month, Request $request)
    {
        $shop = $request->query("shop");
        $carbonMonth = Carbon::parse($month);
        $startDate = $carbonMonth->startOfMonth()->format('Y-m-d');
        $endDate = $carbonMonth->endOfMonth()->format('Y-m-d');

        $sales = DB::table('sales')
            ->select(DB::raw("DATE(created_at) as date"), DB::raw("SUM(grandtotal) as totalRevenue"))
            ->where('shop', $shop)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('created_at', 'desc')
            ->get();

        $soldPackages = DB::table('sold_packages')
            ->select(DB::raw("DATE(created_at) as date"), DB::raw("SUM(grandtotal) as totalRevenue"))
            ->where('shop', $shop)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('created_at', 'desc')
            ->get();

        $salesArray = [];

        foreach ($sales as $sale) {
            $salesArray[] = [
                'date' => $sale->date,
                'totalRevenue' => $sale->totalRevenue,
            ];
        }

        foreach ($soldPackages as $package) {
            if (isset($salesArray[$package->date])) {
                $salesArray[$package->date]['totalRevenue'] += $package->totalRevenue;
            } else {
                $salesArray[$package->date] = [
                    'date' => $package->date,
                    'totalRevenue' => $package->totalRevenue,
                ];
            }
        }

        $data = collect($salesArray)->sortByDesc('date')->values()->toArray();

        return response()->json([
            'success' => true,
            'message' => 'monthly sales retrieved successfully',
            'data' => $data,

        ], 200);
    }
    public function getDailyRevenue($shop)
    {
        $startDate = Carbon::today();
        $endDate = Carbon::tomorrow();

        $totalSales = DB::table('sales')
            ->where('shop', $shop)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('grandtotal');

        $totalPackageSales = DB::table('sold_packages')
            ->where('shop', $shop)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('grandtotal');

        $totalRevenue = $totalSales + $totalPackageSales;

        return $totalRevenue;
    }
    public function getMonthlyRevenue($shop, $startDate)
    {
        $startDate = Carbon::parse($startDate)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        if ($startDate->isAfter($endDate)) {
            $startDate = Carbon::now()->startOfMonth();
        }

        $totalSales = DB::table('sales')
            ->where('shop', $shop)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('grandtotal');

        $totalPackageSales = DB::table('sold_packages')
            ->where('shop', $shop)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('grandtotal');

        $totalRevenue = $totalSales + $totalPackageSales;


        return $totalRevenue;
    }
    public function getYearlyRevenue($shop, $startDate)
    {
        $startDate = Carbon::parse($startDate)->startOfYear();
        $endDate = Carbon::now()->endOfYear();

        if ($startDate->isAfter($endDate)) {
            $startDate = Carbon::now()->startOfYear();
        }

        $totalSales = DB::table('sales')
            ->where('shop', $shop)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('grandtotal');

        $totalPackageSales = DB::table('sold_packages')
            ->where('shop', $shop)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('grandtotal');

        $totalRevenue = $totalSales + $totalPackageSales;


        return $totalRevenue;
    }
    public function getProductsByShopAndQuantity(string $name)
    {
        $stock = Stock::where('stock_shop', $name)
            ->whereColumn('stock_quantity', '<=', 'stock_min_quantity')
            ->withCount([
                'sold_item as stock_quantity' => function ($query) {
                    $query->whereMonth('created_at', now()->month);
                }
            ])
            ->orderBy('stock_quantity', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Low stocks retrieved successfully',
            'data' => $stock,

        ], 200);
    }
    public function totalCustomers()
    {
        $totalCustomers = Customer::count();

        return response()->json([
            'success' => true,
            'message' => 'Low stocks retrieved successfully',

            'data' => [
                'totalcustomers' => $totalCustomers,
                'todaycustomers' => $this->getCustomersAddedToday(),
            ],
        ], 200);
    }
    public function getCustomersAddedToday()
    {
        $today = Carbon::today();
        $customers = Customer::whereDate('created_at', $today)->get()->count();
        return $customers;
    }

}