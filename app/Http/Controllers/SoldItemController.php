<?php

namespace App\Http\Controllers;

use App\Models\sold_item;
use Illuminate\Http\Request;

class SoldItemController extends Controller
{
    public function index()
    {
        $soldItems = sold_item::all();
        return response()->json([
            'success' => true,
            'message' => 'Items retrieved successfully',
            'data' => $soldItems
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $soldItem = new sold_item();
        $soldItem->sale_id = $request->sale_id;
        $soldItem->product_id = $request->product_id;
        $soldItem->quantity = $request->quantity;
        $soldItem->price = $request->price;

        if ($soldItem->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Items added successfully',
                'data' => $soldItem
            ]);
        }
    }
    public function show(string $id)
    {
        $soldItem = sold_item::join('stocks', 'stocks.id', '=', 'sold_items.product_id')
            ->select('sold_items.*', 'stocks.*')
            ->where("sale_id", $id)->get();

        if (!$soldItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item retrieved successfully',
            'data' => $soldItem
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $soldItem = sold_item::find($id);

        if (!$soldItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found',
            ], 404);
        }

        $soldItem->sale_id = $request->sale_id;
        $soldItem->product_id = $request->product_id;
        $soldItem->quantity = $request->quantity;
        $soldItem->price = $request->price;
        $soldItem->save();
        return response()->json([
            'success' => true,
            'message' => 'Sold item updated successfully',
            'data' => $soldItem
        ]);
    }

    public function destroy(sold_item $soldItem)
    {
        $soldItem->delete();

        return redirect()->route('sold_items.index')->with('success', 'Sold item deleted successfully.');
    }
}