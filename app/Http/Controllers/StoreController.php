<?php

namespace App\Http\Controllers;


use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::select('stores.*', 'shop_statuses.status as last_status')
            ->leftJoin('shop_statuses', function ($join) {
                $join->on('stores.id', '=', 'shop_statuses.shop_id')
                    ->whereRaw('shop_statuses.id = (select max(id) from shop_statuses where shop_statuses.shop_id = stores.id)');
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stores
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required'
        ]);
        $storename = Store::where('name', $request->name)->first();
        if ($storename) {
            return response()->json(['success' => false, 'message' => 'The shop name is taken.']);
        }
        $store = new Store();
        $store->name = $request->input('name');
        $store->category = $request->input('category');
        $store->region = $request->input('region');
        $store->city = $request->input('city');
        $store->subcity = $request->input('subcity');
        $store->address = $request->input('address');
        $store->tin_number = $request->input('tin_number');
        $store->latitude = $request->input('latitude');
        $store->longitude = $request->input('longitude');
        $store->description = $request->input('description');
        $store->phone = $request->input('phone');
        $store->status = '0';

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');

            // Generate a unique filename for the image
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the image in the "public" disk using the generated filename
            Storage::disk('public')->put($filename, file_get_contents($image));

            // Set the profile_image field to the generated filename
            $store->profile_image = $filename;
        }

        $store->save();

        return response()->json([
            'success' => true,
            'message' => 'Store created successfully.',
            'data' => $store
        ], 201);
    }

    public function show(Store $store)
    {
        return response()->json([
            'success' => true,
            'data' => $store
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $store = Store::find($id);
        $store->name = $request->input('name');
        $store->manager_id = $request->input('manager_id');
        $store->manager = $request->input('manager');
        $store->category = $request->input('category');
        $store->region = $request->input('region');
        $store->city = $request->input('city');
        $store->subcity = $request->input('subcity');
        $store->address = $request->input('address');
        $store->tin_number = $request->input('tin_number');
        $store->latitude = $request->input('latitude');
        $store->longitude = $request->input('longitude');
        $store->description = $request->input('description');
        $store->phone = $request->input('phone');

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');

            // Generate a unique filename for the image
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the image in the "public" disk using the generated filename
            Storage::disk('public')->put($filename, file_get_contents($image));

            // Set the profile_image field to the generated filename
            $store->profile_image = $filename;
        }

        $store->save();

        return response()->json([
            'success' => true,
            'message' => 'Store updated successfully.',
            'data' => $store
        ], 201);
    }
    public function addmanager(Request $request, string $id)
    {

        $store = Store::find($id);
        $store->manager_id = $request->input('manager_id');
        $store->manager = $request->input('manager');
        $store->save();
        return response()->json([
            'success' => true,
            'message' => 'Manager Added successfully.',
        ], 201);
    }
    public function destroy(string $id)
    {
        $record = Store::find($id);
        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Record not found'], 404);
        }
        $record->delete();
        return response()->json([
            'success' => true,
            'message' => 'Store deleted successfully.'
        ], 200);
    }
}