<?php

namespace App\Http\Controllers;

use App\Models\PriceUpdates;
use App\Models\Replanish;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('limit', 15);
        $stocks = Stock::join('items', 'stocks.item_code', '=', 'items.item_code')
            ->select('stocks.*', 'items.item_image', 'items.item_category', 'items.item_sub_category', 'items.item_brand', 'items.item_status')
            ->orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);
        if ($stocks) {
            return response()->json([
                'success' => true,
                'message' => 'Stocks retrieved successfully',
                'data' => $stocks
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'unable to retrieve stocks',

            ], 404);
        }
    }


    public function shopStocks(Request $request, string $name)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('limit', 15);
        $products = Stock::where('stock_shop', '=', $name)->orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);

    }

    public function store(Request $request)
    {
        // Generate item code
        $itemCode = $request->input('item_code');
        $itemName = $request->input('item_name');
        $stockShop = $request->input('stock_shop');

        $checkStock = Stock::where('item_code', $itemCode)->where('item_name', $itemName)->where('stock_shop', $stockShop)->first();
        if ($checkStock) {
            return response()->json([
                'success' => false,
                'message' => 'The stock already exist in this shop',
            ], 202);
        } else {
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
            if ($stock) {
                return response()->json([
                    'success' => true,
                    'message' => 'Stock created successfully',
                    'data' => $stock
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to create stock',

                ], 202);
            }
        }


    }

    public function show($id)
    {
        $stock = Stock::join('items', 'stocks.item_code', '=', 'items.item_code')
            ->select('stocks.*', 'items.item_image', 'items.item_category', 'items.item_sub_category', 'items.item_brand', 'items.item_status')
            ->where('stocks.id', $id)
            ->first();

        $Replenish = Replanish::where('stock_id', $id)->orderByDesc('id')->take(20)
            ->get();
        $updates = PriceUpdates::where('productid', $id)->orderByDesc('id')->take(20)
            ->get();
        $Availability = Stock::where('item_code', $stock->item_code)
            ->where('stock_status', 'In-stock')
            ->take(20)
            ->get();

        if ($stock) {

            return response()->json([
                'success' => true,
                'message' => 'Stock retrieved successfully',
                'stock' => $stock,
                'replanishments' => $Replenish,
                'priceupdates' => $updates,
                'availablity' => $Availability,
            ], 200);


        } else {
            return response()->json([
                'success' => false,
                'message' => 'unable to retrieved stock',
            ], 202);
        }

    }

    public function update(Request $request, $id)
    {
        $stock = Stock::find($id);

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

        if ($stock) {
            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'data' => $stock
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'unable to updated stock',

            ], 202);
        }

    }



    public function updateStatus(Request $request, $id)
    {
        $stock = Stock::find($id);

        if ($stock) {
            $newStatus = $request->input('new_status');
            $stock->update(['stock_status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $stock
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'stock is not found',

            ], 202);
        }

    }








    public function destroy($id)
    {
        $stock = Stock::find($id);
        $stock->delete();

        if ($stock) {
            return response()->json([
                'success' => true,
                'message' => 'Stock deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'stock item not found'
            ], 202);
        }
    }
}