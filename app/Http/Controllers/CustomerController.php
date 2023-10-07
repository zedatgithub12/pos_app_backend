<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\sold_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'data' => $customers
        ], 200);
    }
    /**
     * Display a lis of customer with sales role
     */
    public function storecustomer(string $name)
    {
        $customers = Customer::where('shop', '=', $name)->get();
        return response()->json([
            'success' => true,
            'data' => $customers
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'shop' => 'required',
        ]);

        $checkExistance = Customer::where('phone', $request->phone)->first();

        if ($checkExistance) {
            return response()->json(['success' => false, 'message' => 'The phone number is already taken'], 422);
        } else {
            $customer = new Customer;
            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $customer->shop = $request->shop;
            $customer->save();

            return response()->json(['success' => true, 'message' => 'Customer added successfully.'], 201);
        }
    }



    public function getCustomerPurchaseDetails(string $id)
    {
        $customers = Customer::find($id);

        if ($customers) {
            $customername = $customers->name;
            // Calculate total spend
            $totalSpend = Sale::where('customer', $customername)->sum('grandtotal');

            // Calculate number of times bought
            $numberOfPurchases = Sale::where('customer', $customername)->count();

            $frequentItems = sold_item::join('sales', 'sold_items.sale_id', '=', 'sales.id')
                ->join('stocks', 'sold_items.product_id', '=', 'stocks.id')
                ->where('sales.customer', $customername)
                ->select('sold_items.product_id', 'stocks.item_code', 'stocks.item_name', DB::raw('SUM(sold_items.quantity) as totalQuantity'))
                ->groupBy('sold_items.product_id', 'stocks.item_code', 'stocks.item_name')
                ->orderByDesc('totalQuantity')
                ->take(5)
                ->get();

            // Get buying record of customer
            $buyingRecord = Sale::where('customer', $customername)->orderByDesc('id')->get();
            return response()->json([
                'success' => true,
                'message' => "Customer Details",
                'total_spend' => $totalSpend,
                'number_of_purchases' => $numberOfPurchases,
                'frequent_items' => $frequentItems,
                'data' => $buyingRecord,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Customer is not found",

            ], 404);
        }



    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $customer = Customer::find($id);

        if ($request->has('name')) {
            $customer->name = $request->name;
        }

        if ($request->has('phone')) {
            $customer->phone = $request->phone;
        }

        if ($request->has('shop')) {
            $customer->shop = $request->shop;
        }
        $customer->save();


        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Record not found'], 404);
        }
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully',
        ], 200);

    }
}