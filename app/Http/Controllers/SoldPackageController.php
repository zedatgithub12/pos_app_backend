<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Product;
use App\Models\SoldPackage;
use App\Models\Stock;
use App\Models\Store;
use Illuminate\Http\Request;

class SoldPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getPackages(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('limit', 15);

        $packages = SoldPackage::orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $packages
        ], 200);
    }

    public function storepackagesale(Request $request, string $name)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('limit', 15);

        $psold = SoldPackage::where('shop', $name)
            ->orderByDesc('id')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'message' => 'Sold packages retrieved successfully',
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
        $sale->grandtotal = $request->grandTotal;
        $sale->payment_status = $request->payment_status;
        $sale->payment_method = $request->payment_method;
        $sale->note = $request->note;
        $sale->reference = $referenceNumber;
        $sale->date = date('Y-m-d');
        $sale->time = date('H:i:s');


        $items = json_decode($sale->items, true);
        foreach ($items as $item) {
            $stock = Stock::find($item['id']);
            $newQuantity = $stock->stock_quantity - $item['quantity'];
            if ($newQuantity < 0) {
                return response()->json(['message' => 'Stock quantity is less than to be sold quantity'], 400);
            }
            $stock->stock_quantity = $newQuantity;
            if ($newQuantity === 0) {
                $stock->status = 'out-stock';
                $shop = Store::where('name', $stock->shop)->first();
                $Notification = new Notification();
                $Notification->title = $stock->name . " is ended!";
                $Notification->time = date('H:i:s');
                $Notification->message = $stock->name . " is out of stock take action please!";
                $Notification->type = 'stock';
                $Notification->itemid = $stock->id;
                $Notification->recipient = $shop->id;
                $Notification->status = "unseen";
                $Notification->salesstatus = "unseen";
                $Notification->save();


            }
            $stock->save();
        }

        $sale->save();

        return response()->json(['success' => true, 'message' => 'Sold successfully', 'reference_number' => $referenceNumber]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sale = SoldPackage::find($id);
        $sale->user = $request->user;
        $sale->shop = $request->shop;
        $sale->customer = $request->customer;
        // $sale->items = json_encode($request->items);
        $sale->grandtotal = $request->grandTotal;
        $sale->payment_status = $request->payment_status;
        $sale->payment_method = $request->payment_method;
        $sale->note = $request->note;

        if ($sale->save()) {
            return response()->json(['success' => true, 'message' => 'Package Sale updated successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Unable to update sale, retry later']);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $packagesale = SoldPackage::find($id);
        if (!$packagesale) {
            return response()->json(['success' => false, 'message' => 'Record not found'], 404);
        }
        if ($packagesale->delete()) {
            return response()->json(['success' => true, 'message' => 'Deleted successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Unable to delete this record']);
        }


    }
}