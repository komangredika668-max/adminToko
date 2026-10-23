<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /** 
     * Display a listing of the resource. 
     */
    public function index()
    {
        // Get all products with their category 
        $products = Product::with('category')->paginate(10);
        return ProductResource::collection($products);
    }

    /** 
     * Store a newly created resource in storage. 
     */
    public function store(Request $request)
    {
        // Validate the request 
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        // Initialize image path 
        $path = "";
        //mengambil file image dari request img 
        if ($request->hasFile('img')) {
            // menyimpan file image ke storage pada folder products di disk public 
            $path = $request->file('img')->store('products', 'public');
        }
        // Create the product 
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            //hanya menyimpan path image ke database 
            'img' => $path,
            'slug' => Str::slug($request->name) // Generate slug from name 
        ]);
        return new ProductResource($product);
    }

    /** 
     * Display the specified resource. 
     */
    public function show(string $id)
    {
        // Get product by ID with its category 
        $product = Product::with('category')->findOrFail($id);
        return new ProductResource($product);
    }

    /** 
     * Update the specified resource in storage. 
     */
    public function update(Request $request, string $id)
    {
        // Find the product 
        $product = Product::findOrFail($id);
        // Validate the request 
        // Validate the request 
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'img' => 'nullable|string'
        ]);
        // Update the product 
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'img' => $request->img,
            'slug' => Str::slug($request->name) // Generate slug from name 
        ]);

        return new ProductResource($product);
    }

    /** 
     * Remove the specified resource from storage. 
     */
    public function destroy(string $id)
    {
        // Find and delete the product 
        $product = Product::find($id);
        if ($product != null) {
            $product->delete();
            return response()->json(['message' => 'Product deleted 
successfully']);
        }
        // Return a success response 
        return response()->json(['message' => 'Product not found'], 404);
    }
}
