<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Product;
use App\Models\SoldPackage;
use App\Models\Store;
use Illuminate\Http\Request;

class SoldPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $psales = SoldPackage::orderByDesc('id')->get();
        return response()->json([
            'success' => true,
            'message' => 'Sold package retrieved successfully',
            'data' => $psales
        ], 200);
    }

    public function storepackagesale(string $name)
    {
        $psold = SoldPackage::where('shop', '=', $name)->get();

        return response()->json([
            'success' => true,
            'message' => 'Sold package retrieved successfully',
            'data' => $psold
        ], 200);


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Generate a unique reference number
        $lastSale = SoldPackage::orderBy('id', 'desc')->first();
        $referenceNumber = $lastSale ? 'REF' . ($lastSale->id + 1) : 'REF1001';

        // Store the sale data and reference number in the database
        $sale = new SoldPackage;
        $sale->user = $request->user;
        $sale->shop = $request->shop;
        $sale->customer = $request->customer;
        $sale->p_name = $request->pname;
        $sale->items = json_encode($request->products);
        $sale->tax = $request->tax;
        $sale->discount = $request->discount;
        $sale->grandtotal = $request->grandTotal;
        $sale->payment_status = $request->payment_status;
        $sale->payment_method = $request->payment_method;
        $sale->note = $request->note;
        $sale->reference = $referenceNumber;
        $sale->date = date('Y-m-d');
        $sale->time = date('H:i:s');


        $items = json_decode($sale->items, true);
        foreach ($items as $item) {
            $product = Product::find($item['id']);
            $newQuantity = $product->quantity - $item['quantity'];
            if ($newQuantity < 0) {
                return response()->json(['message' => 'Stock quantity is less than to be sold quantity'], 400);
            }
            $product->quantity = $newQuantity;
            if ($newQuantity == 0) {
                $product->status = 'out-stock';
                $shop = Store::where('name', $product->shop)->first();
                $Notification = new Notification();
                $Notification->title = $product->name . " is ended!";
                $Notification->time = date('H:i:s');
                $Notification->message = $product->name . " is out of stock take action please!";
                $Notification->type = 'stock';
                $Notification->itemid = $product->id;
                $Notification->recipient = $shop->id;
                $Notification->status = "unseen";
                $Notification->salesstatus = "unseen";
                $Notification->save();


            }
            $product->save();
        }

        $sale->save();

        return response()->json(['success' => true, 'message' => 'Stored successfully', 'reference_number' => $referenceNumber]);
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