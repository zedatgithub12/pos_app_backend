<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminStatController extends Controller
{
    // First, calculate the monthly and annual total earnings and total sales:

    public function Stats(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

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

        // Calculate the monthly earnings and sales
        $monthlyEarnings = DB::table('sales')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->sum('grandtotal');

        $monthlySales = DB::table('sales')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->count();

        // Calculate the annual earnings and sales
        $annualEarnings = DB::table('sales')
            ->whereYear('date', $currentYear)
            ->sum('grandtotal');

        $annualSales = DB::table('sales')
            ->whereYear('date', $currentYear)
            ->count();


        // Get the total number of categories and customers
        $totalproducts = DB::table('products')->where('status', 'In-stock')->count();
        $totalCategories = DB::table('categories')->count();
        $totalCustomers = DB::table('customers')->count();
        // Next, get the 12 products with the highest demand:


        $topProducts = DB::table('products')
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
                'totalProducts' => $totalproducts,
                'totalCategories' => $totalCategories,
                'totalCustomers' => $totalCustomers,
                'topProducts' => $topProducts
            ]
        ], 200);

    }
}