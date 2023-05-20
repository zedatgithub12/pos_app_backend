<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopStatController extends Controller
{
    public function Stats(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $shopName = $request->input('shop');

        // If no preferences are specified, use the current month and year
        if (!$month) {
            $month = date('m');
        }
        if (!$year) {
            $year = date('Y');
        }

        // Get the current year and month
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Calculate the monthly earnings and sales for the given shop
        $monthlyEarnings = DB::table('sales')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('shop', $shopName)
            ->sum('grandtotal');

        $monthlySales = DB::table('sales')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('shop', $shopName)
            ->count();

        // Calculate the annual earnings and sales for the given shop
        $annualEarnings = DB::table('sales')
            ->whereYear('date', $currentYear)
            ->where('shop', $shopName)
            ->sum('grandtotal');

        $annualSales = DB::table('sales')
            ->whereYear('date', $currentYear)
            ->where('shop', $shopName)
            ->count();

        // Get the total number of categories and customers for the given shop
        $totalProducts = DB::table('products')
            ->where('shop', $shopName)
            ->where('status', 'In-stock')
            ->count();
        $totalCategories = DB::table('categories')->count();

        $totalCustomers = DB::table('customers')
            ->where('shop', $shopName)
            ->count();

        // Next, get the 12 products with the highest demand for the given shop:

        $topProducts = DB::table('products')
            ->where('products.shop', $shopName)
            ->orderBy('quantity', 'desc')
            ->take(12)
            ->get();

        // Finally, display the results:

        return response()->json([
            'success' => true,
            'data' => [
                'monthlyEarnings' => $monthlyEarnings,
                'monthlySales' => $monthlySales,
                'annualEarnings' => $annualEarnings,
                'annualSales' => $annualSales,
                'totalProducts' => $totalProducts,
                'totalCategories' => $totalCategories,
                'totalCustomers' => $totalCustomers,
                'topProducts' => $topProducts
            ]
        ], 200);
    }
}