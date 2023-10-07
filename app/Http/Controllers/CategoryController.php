<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Item;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        $subcategories = SubCategory::all();
        $brand = Brand::all();
        $skus = Item::select('item_sku')
            ->whereNotNull('item_sku')
            ->distinct()
            ->pluck('item_sku')
            ->map(function ($sku) {
                return ['item_sku' => $sku];
            });

        return response()->json([
            'success' => true,
            'message' => 'retrived successfully',
            'data' => $categories,
            'subcategory' => $subcategories,
            'brand' => $brand,
            'skus' => $skus
        ], 200);
    }

    public function store(Request $request)
    {
        $category = Category::where('name', $request->name)->first();
        if ($category) {
            return response()->json(['success' => false, 'message' => 'This category already exists.']);
        }
        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return response()->json(['success' => true, 'message' => 'Category added successfully.']);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found.']);
        }

        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return response()->json(['success' => true, 'message' => 'Category updated successfully.']);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found.']);
        }

        $category->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
    }
}