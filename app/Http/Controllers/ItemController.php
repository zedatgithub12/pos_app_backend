<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PriceUpdates;
use App\Models\Replanish;
use App\Models\Stock;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('limit', 15);
        $items = Item::orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);
        return response()->json([
            'success' => true,
            'message' => 'Items retrieved successfully',
            'data' => $items
        ]);
    }

    public function getAllItems()
    {

        $items = Item::orderByDesc('id')->get();
        return response()->json([
            'success' => true,
            'message' => 'Items retrieved successfully',
            'data' => $items
        ]);
    }
    public function store(Request $request)
    {
        // Generate item code
        $categoryId = $request->input('category_id');
        $lastItemId = Item::latest()->first()->id ?? 0;
        $itemCode = $categoryId . ($lastItemId + 1);
        $subCat = $request->input('item_sub_category') !== "Sub Category" ? $request->input('item_sub_category') : "Unsigned";
        // Save the item image
        if ($request->hasFile('item_image')) {
            $itemImage = $request->file('item_image');
            $filename = uniqid() . '.' . $itemImage->getClientOriginalExtension();
            $itemImage->storeAs('public', $filename);
        } else {
            $filename = null;
        }
        // Create the item
        $item = Item::create([
            'item_code' => $itemCode,
            'item_image' => $filename,
            'item_name' => $request->input('item_name'),
            'item_category' => $request->input('item_category'),
            'item_sub_category' => $subCat,
            'item_brand' => $request->input('item_brand'),
            'item_unit' => $request->input('item_unit'),
            'item_price' => $request->input('item_price'),
            'item_description' => $request->input('item_description'),
            'item_status' => 'active'
        ]);
        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Item created successfully',
                'data' => $item
            ], 201);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Cannot add item at the moment',

            ], 202);
        }

    }

    public function show($id)
    {

        $product = Item::find($id);

        $Stock = Replanish::where('stock_id', $id)->orderByDesc('id')->take(20)
            ->get();
        $updates = PriceUpdates::where('productid', $id)->orderByDesc('id')->take(20)
            ->get();
        $Availability = Stock::where('item_code', $product->item_code)
            ->where('stock_status', 'In-stock')
            ->take(20)
            ->get();

        return response()->json([
            'success' => true,
            'product' => $product,
            'replanishments' => $Stock,
            'priceupdates' => $updates,
            'items' => $Availability,
        ]);

    }

    public function update(Request $request, string $id)
    {
        $item = Item::find($id);


        // Save the item image
        if ($request->hasFile('item_image')) {
            $itemImage = $request->file('item_image');
            $filename = uniqid() . '.' . $itemImage->getClientOriginalExtension();
            $itemImage->storeAs('public', $filename);

            $item->item_image = $filename;
        }


        if ($request->has('item_name')) {
            $item->item_name = $request->input('item_name');
        }

        if ($request->has('item_category')) {
            $item->item_category = $request->input('item_category');
        }

        if ($request->has('item_sub_category')) {
            $item->item_sub_category = $request->input('item_sub_category');
        }

        if ($request->has('item_brand')) {
            $item->item_brand = $request->input('item_brand');
        }
        if ($request->has('item_unit')) {
            $item->item_unit = $request->input('item_unit');
        }
        if ($request->has('item_price')) {
            $item->item_price = $request->input('item_price');
        }
        if ($request->has('item_description')) {
            $item->item_description = $request->input('item_description');
        }


        if ($item->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully',
                'data' => $item
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update item at the moment',

            ], 202);
        }
    }

    public function destroy($id)
    {
        $item = Item::find($id);
        if ($item) {
            $item->delete();
            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Item not found'
            ], 404);
        }


    }
}