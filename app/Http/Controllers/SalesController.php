<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Notification;
use App\Events\NewNotification;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $sales = Sale::orderByDesc('id')->get();
        return response()->json([
            'success' => true,
            'message' => 'Sales retrieved successfully',
            'data' => $sales
        ], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function storesale(string $name)
    {
        $sales = Sale::where('shop', '=', $name)->get();

        return response()->json([
            'success' => true,
            'message' => 'Sales retrieved successfully',
            'data' => $sales
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
        // Generate a unique reference number
        $lastSale = Sale::orderBy('id', 'desc')->first();
        $referenceNumber = $lastSale ? 'REF' . ($lastSale->id + 1) : 'REF1001';

        // Store the sale data and reference number in the database
        $sale = new Sale;
        $sale->user = $request->user;
        $sale->shop = $request->shop;
        $sale->customer = $request->customer;
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
                return response()->json(['error' => 'Quantity sold is greater than product quantity.'], 400);
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

        // foreach ($sale->products as $product_sold) {
        //     $product = Product::find($product_sold['id']);
        //     $product->quantity -= $product_sold['quantity'];
        //     $product->save();
        // }
        return response()->json(['success' => true, 'message' => 'Sale created successfully', 'reference_number' => $referenceNumber]);
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
        $sale = Sale::findOrFail($id);
        $sale->user = $request->user;
        $sale->shop = $request->shop;
        $sale->customer = $request->customer;
        $sale->items = json_encode($request->products);
        $sale->tax = $request->tax;
        $sale->discount = $request->discount;
        $sale->grandtotal = $request->grandTotal;
        $sale->payment_status = $request->payment_status;
        $sale->payment_method = $request->payment_method;
        $sale->note = $request->note;

        $sale->save();

        return response()->json(['success' => true, 'message' => 'Sale updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sale = Sale::find($id);
        if (!$sale) {
            return response()->json(['success' => false, 'message' => 'Record not found'], 404);
        }
        $sale->delete();

        return response()->json(['success' => true, 'message' => 'Sale deleted successfully']);
    }
}