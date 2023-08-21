<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($x = 1; $x <= 100; $x++) {
            $randomArrSize = rand(1, 10);

            $randomUserId = rand(User::min('id'), User::max('id'));
            $order = Order::create(['total' => 0, 'status' => 'paid']);
            $order->user()->associate(User::find($randomUserId))->save();

            $total = 0;
            $products = [];

            for ($y = 1; $y <= $randomArrSize; $y++) {
                $randomProductId = rand(Product::min('id'), Product::max('id'));
                
                if(in_array($randomProductId, $products)) {
                    continue;
                }

                $products[] = $randomProductId;
                
                $product = Product::find($randomProductId);
                
                $total += (float)$product->price;

                $item = OrderItem::create(['name' => $product->name, 'price' => $product->price]);
                
                $item->order()->associate($order)->save();
                $item->product()->associate($product)->save();
            }

            $order->update(['total' => $total]);
        }
    }
}
