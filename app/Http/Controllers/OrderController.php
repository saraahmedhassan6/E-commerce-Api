<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->paginate(20);
        if ($orders) {
            foreach ($orders as $order) {
                foreach ($order->items as $order_item) {
                    $product = Product::where('id', $order_item->product_id)->pluck('name');
                    $order_item->product_name = $product['0'];
                }
            }
            return response()->json($orders, 200);
        } else
            return response()->json('There is no Orders');
    }

    public function show($id)
    {
        $order = Order::find($id);
        if ($order) {
            return response()->json($order, 200);
        }
        return response()->json('Order not found');
    }

    public function store(Request $request)
    {
        try {
            $location = Location::where('user_id', Auth::id());
            $request->validate([
                'order_items' => 'required',
                'total_price' => 'required',
                'quantity' => 'required',
                'date_of_delivery' => 'required',
            ]);
            $order = new Order();
            $order->user_id = Auth::id();
            $order->location_id = $location->id;
            $order->total_price = $request->total_price;
            $order->date_of_delivery = $request->date_of_delivery;
            $order->save();

            foreach ($request->order_items as $order_item) {
                $items = new OrderItem();
                $items->order_id = $order->id;
                $items->price = $order_item['price'];
                $items->product_id = $order_item['product_id'];
                $items->quantity = $order_item['quantity'];
                $items->save();

                $product = Product::where('id', $order_item['product_id'])->first();
                $product->quantity = $order_item['quantity'];
                $product->save();
            }
            return response()->json('Ordered is added', 201);

        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function getOrderItems($id)
    {
        $order_items = OrderItem::where('order_id', $id)->get();
        if ($order_items) {
            foreach ($order_items as $order_item) {
                $product = Product::where('id', $order_item->product_id)->pluck('name');
                $order_item->product_name = $product['0'];
            }
            return response()->json($order_items, 200);
        } else
            return response()->json('No Items Found');
    }

    public function getUserOrders($id)
    {
        $orders = Order::where('user_id', $id)
            ->with([
                'items' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->get();

        if ($orders) {
            foreach ($orders->items as $order) {
                $product = Product::where('id', $order->product_id)->pluck('name');
                $order->product_name = $product['0'];
            }
            return response()->json($orders, 200);
        } else
            return response()->json('No Order Found for this User');
    }

    public function changeOrderStatus($id,Request $request)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update(['status'=>$request->status]);
            return response()->json('Status Changed Successfuly');
        } else
        return response()->json('Order Not Found');

    }

}
