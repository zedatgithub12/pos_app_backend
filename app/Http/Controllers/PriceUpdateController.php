<?php

namespace App\Http\Controllers;

use App\Models\PriceUpdates;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Store;

class PriceUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $updates = PriceUpdates::where('productid', $id)->orderByDesc('id')->get();
        return response()->json([
            'success' => true,
            'data' => $updates
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
        $shop = Store::where('name', $request->name)->firstOrFail();
        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Can not find shop id.',
                'data' => $shop
            ], 402);
        }
        $newPrice = new PriceUpdates([
            'productid' => $request->productid,
            'shopid' => $shop->id,
            'from' => $request->from,
            'to' => $request->to,
            'date' => date('Y-m-d'),
            'status' => 'unseen',
        ]);
        $newPrice->save();

        $product = Product::find($request->productid);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'can not update price.',

            ], 402);
        }
        $product->price = $newPrice->to;
        $product->save();


        return response()->json([
            'success' => true,
            'message' => 'Price Updated successfully.',

        ], 201);
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