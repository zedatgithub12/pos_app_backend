<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

        $customer = new Customer;
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->shop = $request->shop;
        $customer->save();

        return response()->json(['success' => true, 'message' => 'Customer added successfully.']);
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