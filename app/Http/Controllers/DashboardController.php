<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ShopTarget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

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

        $salesArray = [];

        foreach ($sales as $sale) {
            $salesArray[] = [
                'date' => $sale->date,
                'totalRevenue' => $sale->totalRevenue,
            ];
        }

        return $salesArray;
    }
    public function getDailyRevenue($shop)
    {
        $startDate = Carbon::today();
        $endDate = Carbon::tomorrow();

        $totalSales = DB::table('sales')
            ->where('shop', $shop)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('grandtotal');

        return $totalSales;
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

        return $totalSales;
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

        return $totalSales;
    }


    // check the sales against target and return the value
    function getSalesAgainstTarget(string $name)
    {
        $shop = $name;
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

    }


    /**
     * select a product with a quantity less than min quantity 
     */
    public function getProductsByShopAndQuantity(string $name)
    {
        $products = Product::where('shop', $name)
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Low stocks retrieved successfully',
            'data' => $products,

        ], 200);
    }

    /**
     * get total customers
     */
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
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}