<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\TransferedItems;
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
            foreach ($request->items as $item) {

                $code = $item["item_code"];
                $quantity = $item["stock_quantity"];

                $senderShopQuantity = $this->getShopItemQuantity($senderShopName, $code); //get sender shop current stock balance

                if ($senderShopQuantity >= $quantity) {
                    $stock = Stock::where('stock_shop', $senderShopName)->where('item_code', $code)->first();
                    $stock->stock_quantity -= $quantity;
                    $stock->save();

                    $this->addQuantityToShop($senderShopName, $receiverShopName, $code, $quantity);
                } else {
                    // Quantity to be transferred is more than available quantity in sender shop
                    $message[] = "Insufficient quantity for item with code: $code";
                }

                $newtransfer = new TransferedItems();
                $newtransfer->transfer_id = $transfer->id;
                $newtransfer->item_name = $item["item_name"];
                $newtransfer->item_code = $item["item_code"];
                $newtransfer->item_unit = $item["stock_unit"];
                $newtransfer->item_price = $item["stock_price"];
                $newtransfer->existing_amount = $item["existing"];
                $newtransfer->transfered_amount = $item["stock_quantity"];
                $newtransfer->save();

            }

            $Notification = new Notification();
            $Notification->title = "Stocks Transfer";
            $Notification->time = date('H:i:s');
            $Notification->message = "Stocks are transferred from " . $request->sendershopname . " to " . $request->receivershopname;
            $Notification->type = 'transfer';
            $Notification->itemid = $transfer->id;
            $Notification->recipient = $receiverShopId;
            $Notification->status = "unseen";
            $Notification->salesstatus = "unseen";
            $Notification->save();

            return response()->json(['success' => true, 'message' => 'Items transfer is done!', 'data' => $items], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Item transfer is faild!', 'data' => $items], 500);
        }

    }

    private function getShopItemQuantity($shop, $code)
    {
        // Implement your logic to retrieve the quantity of the item in the shop
        // Return the quantity
        $ItemInShop = Stock::where('stock_shop', $shop)->where('item_code', $code)->first();
        return $ItemInShop->stock_quantity;
    }

    private function addQuantityToShop($senderShopName, $shop, $code, $quantity)
    {
        $Item = Stock::where('stock_shop', $shop)->where('item_code', $code)->first();

        if ($Item) {
            $Item->stock_quantity += $quantity;
            $Item->save();

            if ($Item->save()) {
                return true;
            } else {
                return false;
            }
        } else {
            // Handle the case where the product is not found
            // For example, you can log an error or throw an exception
            // You can also add a message to the $message array to indicate the issue

            $ItemInShop = Stock::where('stock_shop', $senderShopName)->where('item_code', $code)->first();

            $item = new Stock();
            $item->item_code = $ItemInShop->item_code;
            $item->item_name = $ItemInShop->item_name;
            $item->stock_shop = $shop;
            $item->stock_cost = $ItemInShop->stock_cost;
            $item->stock_unit = $ItemInShop->stock_unit;
            $item->stock_min_quantity = $ItemInShop->stock_min_quantity;
            $item->stock_price = $ItemInShop->stock_price;
            $item->stock_quantity = $quantity;
            $item->stock_expire_date = $ItemInShop->stock_expire_date;
            $item->stock_status = 'In-Stock';

            if ($item->save()) {
                return true;
            } else {
                return false;
            }
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
        $transfer = StockTransfer::find($id);
        if ($transfer) {
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
        } else {

            return response()->json(['success' => false, 'message' => 'The record is not found in database']);

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