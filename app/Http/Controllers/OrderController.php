<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        //$response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->post('https://pikachu.app/api/v1/product/78/order');
        
        $page = $request->input('page');
        if(!$page) {
            $page = 1;
        }
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/orders?page='.$page);
        $return = $response->object();
        $orders = $return->data;
        $meta = $return->meta;
        
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/order/unpaid');
        $return = $response->object();
        $order = $return->data;

        return view('order.index', ['orders' => $orders, 'order' => $order, 'meta' => $meta, 'page' => $page]);
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Product $product
    * @return \Illuminate\Http\Response
    */
    public function show()
    {
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/order/unpaid');
        $return = $response->object();
        $order = $return->data;

        return view('order.show', ['order' => $order]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Product $product)
    {
        $body = new \stdClass();
        $body->name = $product->name;
        $body->price = $product->price;
        
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->withBody(json_encode($body))->post(config('app.url').'/api/v1/product/'.$product->id.'/order');
        
        if($response->successful()) {
            return redirect()->route('product.index')->with('success','Product has been created successfully.');
        }
        
        abort(500);
    }

    public function remove($id)
    {
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->delete(config('app.url').'/api/v1/order/item/'.$id);
        //return dd($response->object());
        if($response->successful()) {
            return redirect()->route('order.index')->with('success','Item has been deleted successfully');
        }

        abort(500);
        
    }

    public function destroy(Order $order)
    {
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->delete(config('app.url').'/api/v1/order/'.$order->id);
        
        if($response->successful()) {
            return redirect()->route('order.index')->with('success','Item has been deleted successfully');
        }

        abort(500);
        
    }
}
