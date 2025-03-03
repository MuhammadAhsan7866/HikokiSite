<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Tag;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    // Fetch all products with pagination
    public function index()
    {
        $products = Product::with('product_category','tags')->get(); // You can adjust the pagination limit
        return response()->json([
            'data' => $products
        ]);
    }

    // Store a new product
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'product_category_id' => 'required|exists:product_categories,id',
                'brand' => 'required|string|max:255',
                'weight' => 'required|string|max:255',
                'tag_number' => 'required|integer',
                'description' => 'required|string|max:1000',
                'stock' => 'required|integer|min:0',
                'tags' => 'required|array|min:1',
                'tags.*' => 'required|exists:tags,id',
                'price' => 'required|numeric|min:0',
            ],
            [
                'title.required' => 'Product name is required.',
                'image.required' => 'Product image is required.',
                'product_category_id.required' => 'Product category is required.',
                'brand.required' => 'Brand name is required.',
                'weight.required' => 'Weight is required.',
                'tag_number.required' => 'Tag number is required.',
                'description.required' => 'Product description is required.',
                'stock.required' => 'Stock quantity is required.',
                'tags.required' => 'At least one tag is required.',
                'price.required' => 'Price is required.',
            ]
        );

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $fileName);
            $imagePath = 'images/products/' . $fileName;
        }

        // Create the product
        $product = Product::create([
            'title' => $request->title,
            'product_category_id' => $request->product_category_id,
            'brand' => $request->brand,
            'weight' => $request->weight,
            'tag_number' => $request->tag_number,
            'description' => $request->description,
            'stock' => $request->stock,
            'price' => $request->price,
            'discount' => $request->discount ?? 0.0,
            'tex' => $request->tex ?? 0.0,
            'image' => $imagePath ?? null,
        ]);

        // Attach tags to the product
        $product->tags()->attach($request->tags);
        $product=$product->find($product->id);
        return response()->json([
            'message' => 'Product Created Successfully',
            'data' => $product
        ], 201); // 201 for created resource
    }

    // Show a single product by ID
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'data' => $product
        ]);
    }

    // Update an existing product
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'product_category_id' => 'required|exists:product_categories,id',
                'brand' => 'required|string|max:255',
                'weight' => 'required|string|max:255',
                'tag_number' => 'required|integer',
                'description' => 'required|string|max:1000',
                'stock' => 'required|integer|min:0',
                'tags' => 'required|array|min:1',
                'tags.*' => 'required|exists:tags,id',
                'price' => 'required|numeric|min:0',
            ],
            [
                'title.required' => 'Product name is required.',
                'image.image' => 'Please upload a valid image.',
                'product_category_id.required' => 'Product category is required.',
                'brand.required' => 'Brand name is required.',
                'weight.required' => 'Weight is required.',
                'tag_number.required' => 'Tag number is required.',
                'description.required' => 'Product description is required.',
                'stock.required' => 'Stock quantity is required.',
                'tags.required' => 'At least one tag is required.',
                'price.required' => 'Price is required.',
            ]
        );

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $fileName);
            $imagePath = 'images/products/' . $fileName;
            $product->image = $imagePath;
        }

        // Update product data
        $product->update([
            'title' => $request->title,
            'product_category_id' => $request->product_category_id,
            'brand' => $request->brand,
            'weight' => $request->weight,
            'tag_number' => $request->tag_number,
            'description' => $request->description,
            'stock' => $request->stock,
            'price' => $request->price,
            'discount' => $request->discount ?? 0.0,
            'tex' => $request->tex ?? 0.0,
        ]);

        // Update tags for the product
        $product->tags()->sync($request->tags);
        $product=$product->find($product->id);
        return response()->json([
            'message' => 'Product Updated Successfully',
            'data' => $product
        ]);
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product Deleted Successfully'
        ]);
    }
}
