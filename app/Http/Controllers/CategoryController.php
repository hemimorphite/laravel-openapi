<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;

class CategoryController extends Controller
{   
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {

        $page = $request->input('page');
        if(!$page) {
            $page = 1;
        }
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/categories?page='.$page);
        $return = $response->object();
        $categories = $return->data;
        $meta = $return->meta;
        
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/order/unpaid');
        $return = $response->object();
        $order = $return->data;

            
        return view('category.index', ['categories' => $categories, 'order' => $order, 'meta' => $meta, 'page' => $page]);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(CategoryRequest $request)
    {
        //if($request->session()->get('errors')) {
            //return dd($request->session()->get('errors'));
        //}

        //if($request->session()->getOldInput()) {
            //return dd($request->session()->getOldInput());
        //}
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/order/unpaid');
        $return = $response->object();
        $order = $return->data;

        return view('category.create', ['order' => $order]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(CategoryRequest $request)
    {
        $body = new \stdClass();
        $body->name = $request->input('name');

        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->withBody(json_encode($body))->post(config('app.url').'/api/v1/category');
        
        if($response->successful()) {
            return redirect()->route('category.index')->with('success','Category has been created successfully.');
        }

        if($response->status() == 422) {
            $return = $response->object();
            return redirect()->route('category.create')->withInput()->with('errors', $return->errors);
        }

        abort(500);
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Category  $category
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        return;
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Category  $category
    * @return \Illuminate\Http\Response
    */
    public function edit(Category $category)
    {
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/category/'.$category->id);
        $return = $response->object();
        $category = $return->data;
        
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->get(config('app.url').'/api/v1/order/unpaid');
        $return = $response->object();
        $order = $return->data;

        return view('category.edit',['category' => $category, 'order' => $order]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Category  $category
    * @return \Illuminate\Http\Response
    */
    public function update(CategoryRequest $request, Category $category)
    {
        $body = new \stdClass();
        $body->id = $category->id;
        $body->name = $request->input('name');

        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->withBody(json_encode($body))->patch(config('app.url').'/api/v1/category/'.$category->id);
        
        if($response->successful()) {
            return redirect()->route('category.index')->with('success','Category Has Been updated successfully');
        }

        if($response->status() == 422) {
            $return = $response->object();
            return redirect()->route('category.edit', ['id' => $category->id])->withInput()->with('errors', $return->errors);
        }

        abort(500);
        
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Category  $category
    * @return \Illuminate\Http\Response
    */
    public function destroy(Category $category)
    {
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->delete(config('app.url').'/api/v1/category/'.$category->id);
        
        if($response->successful()) {
            return redirect()->route('category.index')->with('success','Category has been deleted successfully');
        }

        if($response->status() == 404 || $response->status() == 403) {
            $return = $response->object();
            return redirect()->route('category.index')->with('failed', $return->message);
        }

        abort(500);
        
    }
}
