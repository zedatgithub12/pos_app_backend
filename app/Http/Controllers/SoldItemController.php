<?php

namespace App\Http\Controllers;

use App\Models\sold_item;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SoldItemController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->query('shop');
        $page = $request->query('page', 1);
        $perPage = $request->query('limit', 15);
        if ($shop !== 'All') {
            $soldItems = sold_item::join('stocks', 'stocks.id', '=', 'sold_items.product_id')
                ->join('items', 'items.item_code', '=', 'stocks.item_code')
                ->select('sold_items.*', 'stocks.item_code', 'stocks.item_name', 'stocks.stock_shop', 'stocks.stock_cost', 'stocks.stock_price', 'stocks.stock_min_quantity', 'stocks.stock_expire_date', 'items.item_category', 'items.item_sub_category', 'items.item_brand', 'items.item_unit', 'items.item_sku')->where('stock_shop', $shop)
                ->orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'message' => 'Items retrieved successfully',
                'data' => $soldItems
            ]);
        } else {
            $soldItems = sold_item::join('stocks', 'stocks.id', '=', 'sold_items.product_id')
                ->join('items', 'items.item_code', '=', 'stocks.item_code')
                ->select('sold_items.*', 'stocks.item_code', 'stocks.item_name', 'stocks.stock_shop', 'stocks.stock_cost', 'stocks.stock_price', 'stocks.stock_min_quantity', 'stocks.stock_expire_date', 'items.item_category', 'items.item_sub_category', 'items.item_brand', 'items.item_unit', 'items.item_sku')
                ->orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'message' => 'Items retrieved successfully',
                'data' => $soldItems
            ]);
        }
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
    public function show(Request $request, string $id)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('limit', 15);
        $soldItem = sold_item::join('stocks', 'stocks.id', '=', 'sold_items.product_id')
            ->join('items', 'items.item_code', '=', 'stocks.item_code')
            ->select('sold_items.*', 'stocks.item_code', 'stocks.item_name', 'stocks.stock_shop', 'stocks.stock_cost', 'stocks.stock_price', 'stocks.stock_min_quantity', 'stocks.stock_expire_date', 'items.item_category', 'items.item_sub_category', 'items.item_brand', 'items.item_unit', 'items.item_sku')
            ->where("sale_id", $id)->orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);
        ;

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

    public function filterSoldItem(Request $request)
    {
        $shop = $request->query('shop');
        $startingFrom = $request->query('startingfrom');
        $to = $request->query('to');

        // Set initial starting date and today's date if values are not passed
        if (empty($startingFrom)) {
            $startingFrom = Carbon::now()->startOfMonth()->toDateString();
        }

        if (empty($to)) {
            $to = Carbon::today()->toDateString();
        }

        if ($shop !== 'All') {
            $soldItems = sold_item::join('stocks', 'stocks.id', '=', 'sold_items.product_id')
                ->join('items', 'items.item_code', '=', 'stocks.item_code')
                ->select('sold_items.*', 'stocks.item_code', 'stocks.item_name', 'stocks.stock_shop', 'stocks.stock_cost', 'stocks.stock_price', 'stocks.stock_min_quantity', 'stocks.stock_expire_date', 'items.item_category', 'items.item_sub_category', 'items.item_brand', 'items.item_unit', 'items.item_sku')->where('stock_shop', $shop)->orderByDesc('id')
                ->whereBetween('sold_items.created_at', [$startingFrom, $to])
                ->get();

            if ($soldItems) {
                $categorySumArray = $this->getCategorySum($soldItems);
                return response()->json([
                    'success' => true,
                    'message' => 'Sold retrieved from the shop',
                    'data' => $categorySumArray
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch sold items',
                ]);
            }

        } else {
            $soldItems = sold_item::join('stocks', 'stocks.id', '=', 'sold_items.product_id')
                ->join('items', 'items.item_code', '=', 'stocks.item_code')
                ->select('sold_items.*', 'stocks.item_code', 'stocks.item_name', 'stocks.stock_shop', 'stocks.stock_cost', 'stocks.stock_price', 'stocks.stock_min_quantity', 'stocks.stock_expire_date', 'items.item_category', 'items.item_sub_category', 'items.item_brand', 'items.item_unit', 'items.item_sku')
                ->orderByDesc('id')
                ->whereBetween('sold_items.created_at', [$startingFrom, $to])->get();

            if ($soldItems) {
                $categorySumArray = $this->getCategorySum($soldItems);
                return response()->json([
                    'success' => true,
                    'message' => 'Sold Items retrieved from all shop',
                    'data' => $categorySumArray
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch sold items',
                ]);
            }
        }
    }

    public function getCategorySum($data)
    {
        $categorySum = [];

        foreach ($data as $item) {
            $item_category = $item['item_category'];
            $price = $item['price'];

            if (array_key_exists($item_category, $categorySum)) {
                $categorySum[$item_category] += floatval($price);
            } else {
                $categorySum[$item_category] = floatval($price);
            }
        }

        $result = collect($categorySum)->map(function ($sum, $category) {
            return [
                'category' => $category,
                'sum' => $sum
            ];
        })->values();

        return $result->toArray();
    }


    public function ExportSoldItems(Request $request)
    {
        $shop = $request->query('shop');
        $startingFrom = $request->query('startingfrom');
        $to = $request->query('to');

        // Set initial starting date and today's date if values are not passed
        if (empty($startingFrom)) {
            $startingFrom = Carbon::now()->startOfMonth()->toDateString();
        }

        if (empty($to)) {
            $to = Carbon::today()->toDateString();
        }

        if ($shop !== 'All') {
            $soldItems = sold_item::join('stocks', 'stocks.id', '=', 'sold_items.product_id')
                ->join('items', 'items.item_code', '=', 'stocks.item_code')
                ->select('sold_items.*', 'stocks.item_code', 'stocks.item_name', 'stocks.stock_shop', 'stocks.stock_cost', 'stocks.stock_price', 'stocks.stock_min_quantity', 'stocks.stock_expire_date', 'items.item_category', 'items.item_sub_category', 'items.item_brand', 'items.item_unit', 'items.item_sku')->where('stock_shop', $shop)->orderByDesc('id')
                ->whereBetween('sold_items.created_at', [$startingFrom, $to])
                ->get();

            if ($soldItems) {

                return response()->json([
                    'success' => true,
                    'message' => 'Sold retrieved from the shop',
                    'data' => $soldItems
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch sold items',
                ]);
            }

        } else {
            $soldItems = sold_item::join('stocks', 'stocks.id', '=', 'sold_items.product_id')
                ->join('items', 'items.item_code', '=', 'stocks.item_code')
                ->select('sold_items.*', 'stocks.item_code', 'stocks.item_name', 'stocks.stock_shop', 'stocks.stock_cost', 'stocks.stock_price', 'stocks.stock_min_quantity', 'stocks.stock_expire_date', 'items.item_category', 'items.item_sub_category', 'items.item_brand', 'items.item_unit', 'items.item_sku')
                ->orderByDesc('id')
                ->whereBetween('sold_items.created_at', [$startingFrom, $to])->get();

            if ($soldItems) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sold Items retrieved from all shop',
                    'data' => $soldItems
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch sold items',
                ]);
            }
        }
    }
}