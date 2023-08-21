<?php

namespace App\Http\Controllers;

use App\Models\PackagedItem;
use App\Models\Packages;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('limit', 15);
        $packages = Packages::orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);
        return response()->json([
            'success' => true,
            'data' => $packages
        ], 200);
    }

    public function storepackages(Request $request, string $id)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('limit', 15);
        $packages = Packages::where('shopid', '=', $id)->orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $packages
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $package = new Packages();
        $package->shopid = $request->shopid;
        $package->shopname = $request->shop;
        $package->userid = $request->userid;
        $package->name = $request->name;
        $package->items = json_encode($request->items);
        $package->price = $request->price;
        $package->expiredate = $request->expiredate;
        $package->status = "active";

        if ($package->save()) {
            foreach ($request->items as $item) {
                $packagedItem = new PackagedItem();
                $packagedItem->package_id = $package->id;
                $packagedItem->stock_id = $item["id"];
                $packagedItem->item_name = $item["item_name"];
                $packagedItem->item_code = $item["item_code"];
                $packagedItem->item_sku = $item["item_sku"];
                $packagedItem->item_quantity = $item["item_quantity"];
                $packagedItem->save();
            }
            return response()->json(['success' => true, 'message' => 'Package created successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'unable to create package']);
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
        $packages = Packages::find($id);



        if ($request->has('shopid')) {
            $packages->shopid = $request->shopid;
        }

        if ($request->has('shopname')) {
            $packages->shopname = $request->shopname;
        }

        if ($request->has('userid')) {
            $packages->userid = $request->userid;
        }
        if ($request->has('name')) {
            $packages->name = $request->name;
        }
        if ($request->has('items')) {
            $packages->items = json_encode($request->items);
        }

        if ($request->has('price')) {
            $packages->price = $request->price;
        }

        if ($request->has('expiredate')) {
            $packages->expiredate = $request->expiredate;
        }

        $packages->save();

        return response()->json(['success' => true, 'message' => 'Package updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $package = Packages::find($id);

        if (!$package) {
            return response()->json(['success' => false, 'message' => 'Package not found'], 404);
        }

        // Delete the dependent records from the `packaged_items` table
        PackagedItem::where('package_id', $id)->delete();

        // Delete the package from the `packages` table
        $package->delete();

        return response()->json(['success' => true, 'message' => 'Package deleted successfully']);
    }
}