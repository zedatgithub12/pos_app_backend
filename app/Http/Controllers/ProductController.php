<?php

namespace App\Http\Controllers;

use App\Models\PriceUpdates;
use App\Models\Replanish;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('limit', 15);
        $products = Product::orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);

    }
    /**
     * Display a listing of items in store
     */public function storeproduct(Request $request, string $name)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('limit', 15);
        $products = Product::where('shop', '=', $name)->orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'picture' => 'required|image',
        //     'name' => 'required|string',
        //     'category' => 'required|string',
        //     'brand' => 'required|string',
        //     'code' => 'nullable|string',
        //     'cost' => 'required|numeric',
        //     'unit' => 'required|string',
        //     'price' => 'required|numeric',
        //     'quantity' => 'required|numeric',
        //     'description' => 'nullable|string',
        //     'shop' => 'required|string',
        //     'status' => 'required|string',
        // ]);

        $product = new Product();
        $product->name = $request->name;
        $product->category = $request->category;
        $product->sub_category = $request->sub_category;
        $product->brand = $request->brand;
        $product->code = $request->code;
        $product->cost = $request->cost;
        $product->unit = $request->unit;
        $product->price = $request->price;
        $product->min_quantity = $request->min_quantity;
        $product->origional_quantity = $request->quantity;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->shop = $request->shop;
        $product->status = $request->status;


        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $filename = uniqid() . '.' . $picture->getClientOriginalExtension();

            // Store the image in the "public" disk using the generated filename
            Storage::disk('public')->put($filename, file_get_contents($picture));

            $product->picture = $filename;
        }
        $product->save();

        return response()->json(['success' => true, 'message' => 'Product added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        $Stock = Replanish::where('stock_id', $id)->orderByDesc('id')->take(20)
            ->get();
        $updates = PriceUpdates::where('productid', $id)->orderByDesc('id')->take(20)
            ->get();
        $Availability = Stock::where('code', $product->code)
            ->where('status', 'In-stock')
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $product = Product::find($id);

        if ($request->has('name')) {
            $product->name = $request->name;
        }

        if ($request->has('category')) {
            $product->category = $request->category;
        }

        if ($request->has('sub_category')) {
            $product->sub_category = $request->sub_category;
        }
        if ($request->has('brand')) {
            $product->brand = $request->brand;
        }

        if ($request->has('code')) {
            $product->code = $request->code;
        }

        if ($request->has('cost')) {
            $product->cost = $request->cost;
        }

        if ($request->has('unit')) {
            $product->unit = $request->unit;
        }

        if ($request->has('price')) {
            $product->price = $request->price;
        }

        if ($request->has('quantity')) {
            $product->quantity = $request->quantity;
        }

        if ($request->has('min_quantity')) {
            $product->min_quantity = $request->min_quantity;
        }
        if ($request->has('description')) {
            $product->description = $request->description;
        }

        if ($request->has('shop')) {
            $product->shop = $request->shop;
        }

        if ($request->has('status')) {
            $product->status = $request->status;
        }

        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $path = Storage::putFile('public/products', $picture);
            $product->picture = Storage::url($path);
        }

        $product->save();

        return response()->json(['success' => true, 'message' => 'Product updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Record not found'], 404);
        }
        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }
}