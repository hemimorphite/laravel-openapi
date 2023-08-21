<?php

namespace App\Http\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderService {

    public function store(Product $product): Order
    {
        $order = Order::with('items')->where('user_id', Auth::user()->id)->where('status', 'unpaid')->first();
        
        if(!$order) {
            $order = Order::create(['total' => 0]);
            $order->user()->associate(Auth::user());
            $order->save();    
        }
        
        $total = (float) $order->total + (float) $product->price;
        $order->update(['total' => $total]);

        $orderItem = OrderItem::create(['name' => $product->name, 'price' => $product->price]);
        $orderItem->order()->associate($order)->save();
        $orderItem->product()->associate($product)->save();
        

        $order = Order::with('items')->find($order->id);

        return $order;
    }

    public function remove(OrderItem $item): ?Order
    {
        $order = Order::with('items')->where('user_id', Auth::user()->id)->where('status', 'unpaid')->first();
        
        if($order) {
            $total = (float) $order->total - (float) $item->price;
            $order->update(['total' => $total]);

            $order->items()->where('id', $item->id)->delete();

            if(!$order->items()->exists()) {
                $order->delete();
            }
        }

        return $order;
    }

    public function update(): ?Order
    {
        $order = Order::with('items')->where('user_id', Auth::user()->id)->where('status', 'unpaid')->first();

        if($order) {
            $order->update(['status' => 'paid']);
        }

        return $order;
    }
}