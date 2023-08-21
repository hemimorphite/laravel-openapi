<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        //$response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->post('https://pikachu.app/api/v1/product/16/order');
        
        //return dd($response->object());
        //return dd(Auth::user()->api_token);
        $page = $request->input('page');
        if(!$page) {
            $page = 1;
        }
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/categories/all');
        $return = $response->object();
        $categories = $return->data;
        
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/products?page='.$page);
        $return = $response->object();
        $products = $return->data;
        $meta = $return->meta;
        
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/order/unpaid');
        $return = $response->object();
        $order = $return->data;
        
        return view('product.index', ['products' => $products, 'categories' => $categories, 'order' => $order, 'meta' => $meta, 'page' => $page]);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/categories/all');
        
        $return = $response->object();
        $categories = $return->data;

        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/order/unpaid');
        $return = $response->object();
        $order = $return->data;

        return view('product.create', ['categories' => $categories, 'order' => $order]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(ProductRequest $request)
    {
        $body = new \stdClass();
        $body->name = $request->input('name');
        $body->price = $request->input('price');
        $body->categories = [];
        
        $categories = $request->input('categories');

        foreach($categories as $i => $categoryId) {
            $body->categories[$i] = new \stdClass();
            $body->categories[$i]->id = $categoryId;
        }
        
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->withBody(json_encode($body))->post(config('app.url').'/api/v1/product');
        
        if($response->successful()) {
            return redirect()->route('product.index')->with('success','Product has been created successfully.');
        } 

        if($response->status() == 422) {
            $return = $response->object();
            
            return redirect()->route('product.create')->withInput()->with('errors', $return->errors);
        }
        
        abort(500);
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Product $product
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        return;
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Product $product
    * @return \Illuminate\Http\Response
    */
    public function edit(Product $product)
    {
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/product/'.$product->id);
        $return = $response->object();

        $product = $return->data;

        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/categories/all');
        $return = $response->object();
        $categories = $return->data;
        
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/order/unpaid');
        $return = $response->object();
        $order = $return->data;

        return view('product.edit',['product' => $product, 'categories' => $categories, 'order' => $order]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Product $product
    * @return \Illuminate\Http\Response
    */
    public function update(ProductRequest $request, Product $product)
    {
        $body = new \stdClass();
        $body->id = $product->id;
        $body->name = $request->input('name');
        $body->price = $request->input('price');
        $body->categories = [];
        
        $categories = $request->input('categories');

        foreach($categories as $i => $categoryId) {
            $body->categories[$i] = new \stdClass();
            $body->categories[$i]->id = $categoryId;
        }

        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->withBody(json_encode($body))->patch(config('app.url').'/api/v1/product/'.$product->id);
        
        if($response->successful()) {
            return redirect()->route('product.index')->with('success','Product Has Been updated successfully');
        }

        if($response->status() == 422) {
            $return = $response->object();
            return redirect()->route('product.edit', $product->id)->withInput()->with('errors', $return->errors);
        }

        abort(500);
        
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Product $product
    * @return \Illuminate\Http\Response
    */
    public function destroy(Product $product)
    {
        
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->delete(config('app.url').'/api/v1/product/'.$product->id);
        
        if($response->successful()) {
            return redirect()->route('product.index')->with('success','Product has been deleted successfully');
        }

        abort(500);
        
    }
}
