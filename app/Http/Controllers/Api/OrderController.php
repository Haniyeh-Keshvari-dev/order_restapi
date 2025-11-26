<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('items');
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        $query->orderBy('created_at', 'desc');

        $perPage = (int)$request->get('per_page', 15);

        return response()->json(
            $query->paginate($perPage),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $customerId = $data['customer_id'];
        $items = $data['items'];

        DB::beginTransaction();

        try {
            $totalamount = 0;
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }
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

                $product->decrement('stock_quantity', $item['quantity']);
            }
            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('items'),
            ], 201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return response()->json([
            'order' => $order->load('items')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            if (isset($data['customer_id'])) {
                $order->customer_id = $data['customer_id'];
            }

            if (isset($data['items'])) {

                $order->items()->delete();

                $totalPrice = 0;

                foreach ($data['items'] as $item) {

                    $product = Product::find($item['product_id']);
                    $itemPrice = $product->price * $item['quantity'];

                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                    ]);

                    $totalPrice += $itemPrice;
                }

                $order->total_amount = $totalPrice;
            }

            $order->save();

            DB::commit();

            return response()->json([
                'message' => 'Order updated successfully',
                'order' => $order->load('items')
            ], 200);

        } catch (\Exception $exception) {

            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update order',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json([
            'message' => 'Order deleted successfully',
        ],200);
    }
}
