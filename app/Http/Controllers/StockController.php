<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::join('items', 'stocks.item_code', '=', 'items.item_code')
            ->select('stocks.*', 'items.item_picture','items.item_name', 'items.item_category')
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Stocks retrieved successfully',
            'data' => $stocks
        ]);
    }

    public function store(Request $request)
    {
        // Generate item code
        $itemCode = $request->input('item_code');
        $itemName = $request->input('item_name');

        // Create the stock
        $stock = Stock::create([
            'item_code' => $itemCode,
            'item_name' => $itemName,
            'stock_shop' => $request->input('stock_shop'),
            'stock_cost' => $request->input('stock_cost'),
            'stock_unit' => $request->input('stock_unit'),
            'stock_min_quantity' => $request->input('stock_min_quantity'),
            'stock_price' => $request->input('stock_price'),
            'stock_quantity' => $request->input('stock_quantity'),
            'stock_expire_date' => $request->input('stock_expire_date'),
            'stock_status' => $request->input('stock_status')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock created successfully',
            'data' => $stock
        ], 201);
    }

    public function show($id)
    {
        $stock = Stock::join('items', 'stocks.item_code', '=', 'items.item_code')
            ->select('stocks.*', 'items.item_name', 'items.item_category')
            ->where('stocks.id', $id)
            ->first();
        return response()->json([
            'success' => true,
            'message' => 'Stock retrieved successfully',
            'data' => $stock
        ]);
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

        $stock->update([
            'stock_shop' => $request->input('stock_shop'),
            'stock_cost' => $request->input('stock_cost'),
            'stock_unit' => $request->input('stock_unit'),
            'stock_min_quantity' => $request->input('stock_min_quantity'),
            'stock_price' => $request->input('stock_price'),
            'stock_quantity' => $request->input('stock_quantity'),
            'stock_expire_date' => $request->input('stock_expire_date'),
            'stock_status' => $request->input('stock_status')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully',
            'data' => $stock
        ]);
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stock deleted successfully'
        ]);
    }
}