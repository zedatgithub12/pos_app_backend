<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransfer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\HttpCache\Store;

class StockTransferController extends Controller
{
    public function index()
    {
        $transfers = StockTransfer::orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'data' => $transfers
        ], 200);

    }
    public function transferItems(Request $request)
    {
        $senderShopId = $request->sendershopid;
        $senderShopName = $request->sendershopname;
        $receiverShopId = $request->receivershopid;
        $receiverShopName = $request->receivershopname;
        $items = $request->items;

        $message = [];

        foreach ($items as $item) {
            $code = $item['code'];
            $quantity = $item['quantity'];

            // Check if the quantity to be transferred is less than the quantity in the sender shop
            $senderShopQuantity = $this->getShopItemQuantity($senderShopName, $code);

            if ($quantity <= $senderShopQuantity) {
                // Deduct the quantity from sender shop
                $this->deductQuantityFromShop($senderShopName, $code, $quantity);

                // Add the quantity to receiver shop
                $this->addQuantityToShop($receiverShopName, $code, $quantity);
            } else {
                // Quantity to be transferred is more than available quantity in sender shop
                $message[] = "Insufficient quantity for item with code: $code";
            }
        }

        // Store the message object and return at the end of transfer
        $transfer = new StockTransfer();
        $transfer->sendershopid = $senderShopId;
        $transfer->sendershopname = $request->sendershopname;
        $transfer->receivershopid = $receiverShopId;
        $transfer->receivershopname = $request->receivershopname;
        $transfer->items = json_encode($items);
        $transfer->note = $request->note;
        $transfer->userid = $request->userid;
        $transfer->status = count($message) > 0 ? 'partially' : 'done';

        if ($transfer->save()) {
            return response()->json(['success' => true, 'message' => 'Items transfer is done!', 'data' => $items], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Items transfer is failed!', 'data' => $items], 500);

        }

    }

    private function getShopItemQuantity($shop, $code)
    {
        // Implement your logic to retrieve the quantity of the item in the shop
        // Return the quantity
        $ItemInShop = Product::where('shop', $shop)->where('code', $code)->first();
        return $ItemInShop->quantity;
    }

    private function deductQuantityFromShop($shop, $code, $quantity)
    {
        // Implement your logic to deduct the quantity from the shop
        $product = Product::where('shop', $shop)->where('code', $code)->first();
        $product->quantity -= $quantity;
        $product->save();
    }

    private function addQuantityToShop($shop, $code, $quantity)
    {
        $product = Product::where('shop', $shop)->where('code', $code)->first();

        if ($product) {
            $product->quantity += $quantity;
            $product->save();
        } else {
            // Handle the case where the product is not found
            // For example, you can log an error or throw an exception
            // You can also add a message to the $message array to indicate the issue
            $message[] = "Item with code: $code not found in the shop: $shop";
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transfer = StockTransfer::findOrFail($id);

        if ($request->has('sendershopid')) {
            $transfer->sendershopid = $request->sendershopid;
        }
        if ($request->has('sendershopname')) {
            $transfer->sendershopname = $request->sendershopname;
        }
        if ($request->has('receivershopid')) {
            $transfer->receivershopid = $request->receivershopid;
        }

        if ($request->has('receivershopname')) {
            $transfer->receivershopname = $request->receivershopname;
        }

        if ($request->has('note')) {
            $transfer->note = $request->note;
        }
        if ($request->has('userid')) {
            $transfer->userid = $request->userid;
        }
        if ($transfer->save()) {
            return response()->json(['success' => true, 'message' => 'Transfer updated successfully']);

        } else {
            return response()->json(['success' => false, 'message' => 'Cannot update transfer']);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transfers = StockTransfer::find($id);
        if (!$transfers) {
            return response()->json(['success' => false, 'message' => 'Record not found'], 404);
        }
        $transfers->delete();

        return response()->json(['success' => true, 'message' => 'Transfer deleted successfully']);
    }
}