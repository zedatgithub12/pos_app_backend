<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\PriceUpdates;
use App\Models\Product;
use App\Models\Stock;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $shop = Store::where('name', $request->name)->first();
        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Can not find shop id.',
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

        $product = Stock::find($request->productid);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Can not find the item',

            ], 402);
        }
        $product->stock_price = $newPrice->to;
        $product->save();

        $shop = Store::where('name', $product->stock_shop)->first();
        $Notification = new Notification();
        $Notification->title = $product->item_name . " Price is Updated";
        $Notification->time = date('H:i:s');
        $Notification->message = "Changed from " . $request->from . " to " . $newPrice->to;
        $Notification->type = 'stock';
        $Notification->itemid = $product->id;
        $Notification->recipient = $shop->id;
        $Notification->status = "unseen";
        $Notification->salesstatus = "unseen";

        $Notification->save();
        return response()->json([
            'success' => true,
            'message' => 'Price Updated successfully.',

        ], 201);
    }


    public function updateallprice(Request $request)
    {
        $shop = Store::where('name', $request->name)->first();
        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Can not find shop id.',
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
        $status = "In-Stock";
        $product = Stock::where('item_code', $request->productcode)->where('stock_status', $status)->get();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'can not apply price update to all shops',

            ], 402);
        }

        // Update the price for each product
        foreach ($product as $stock) {
            $stock->stock_price = $newPrice->to;
            $stock->save();

            $shop = Store::where('name', $stock->stock_shop)->first();
            if ($shop) {
                $Notification = new Notification();
                $Notification->title = $stock->item_name . "Price is Updated";
                $Notification->time = date('H:i:s');
                $Notification->message = "Changed from " . $request->from . " to " . $newPrice->to;
                $Notification->type = 'stock';
                $Notification->itemid = $stock->id;
                $Notification->recipient = $shop->id;
                $Notification->status = "seen";
                $Notification->salesstatus = "unseen";

                $Notification->save();
            }

        }


        return response()->json([
            'success' => true,
            'message' => 'Price update applied to all item succeed',

        ], 201);
    }



}