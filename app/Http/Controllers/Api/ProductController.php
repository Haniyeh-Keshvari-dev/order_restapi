<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('sku')) {
            $query->where('sku', 'like', '%' . $request->sku . '%');
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sortby = $request->get('sort_by', 'created_at');
        $sortdir = $request->get('sort_dir', 'desc');

        $query->orderBy($sortby, $sortdir);

        $perpage = (int)$request->get('per_page',15);

        return response()->json($query->paginate($perpage), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
//        $product = Product::create([
//                'name' => $request->name,
//                'sku' => $request->sku,
//                'price' => $request->price,
//                'stock_quantity' => $request->stock_quantity
//            ]
//        );
//        return response()->json([
//            'message' => 'Product created successfully',
//            'product' => $product
//
//        ],201);

        $validatedData = $request->validated();
        $product = Product::create($validatedData);
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product

        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();
        $product->update($validatedData);
        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'Product deleted successfully',
        ], 200);
    }
}
