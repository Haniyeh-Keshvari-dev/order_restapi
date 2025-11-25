<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $customerId = $data['customer_id'];
        $items = $data['items'];

        $totalamount = 0;
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            $totalamount += $product['price'] * $item['quantity'];
        }
        $order = Order::create([
            'customer_id' => $customerId,
            'total_amount' => $totalamount,
        ]);

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $product['price'],
            ]);
        }
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order->load('items'),
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Order $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $orders)
    {
        //
    }
}
