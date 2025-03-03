<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
class ProductCategoryController extends Controller
{
    // Fetch all product categories with pagination
    public function index()
    {
        $product_categories = ProductCategory::select('id','name')->get();
        return response()->json([
            'data' => $product_categories
        ]);
    }

    // Store a new product category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ], [
            'name.required' => 'Category Name is Required'
        ]);

        $product_category = ProductCategory::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Product Category Created Successfully',
        ], 200); // Return with 200 status code for created resource
    }

    // Show a single product category by id
    public function show($id)
    {
        $product_category = ProductCategory::find($id);

        if (!$product_category) {
            return response()->json([
                'message' => 'Product Category not found'
            ], 404); // Return 404 if category doesn't exist
        }

        return response()->json([
            'data' => $product_category
        ]);
    }

    // Update an existing product category
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string'
        ], [
            'name.required' => 'Category Name is Required'
        ]);

        $product_category = ProductCategory::find($id);

        if (!$product_category) {
            return response()->json([
                'message' => 'Product Category not found'
            ], 404);
        }

        $product_category->name = $request->name;
        $product_category->save();

        return response()->json([
            'message' => 'Product Category Updated Successfully',
            'data' => $product_category
        ]);
    }

    // Delete a product category
    public function destroy($id)
    {
        $product_category = ProductCategory::find($id);

        if (!$product_category) {
            return response()->json([
                'message' => 'Product Category not found'
            ], 404);
        }

        $product_category->delete();

        return response()->json([
            'message' => 'Product Category Deleted Successfully'
        ]);
    }
}
