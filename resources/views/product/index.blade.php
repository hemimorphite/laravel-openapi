@extends('layout')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12">
            <h4>Product Table</h4>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width:3rem;">No</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Category</th>
                <th style="width:15rem;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $i => $product)
            @php 
                $cats = array_map(function($category) { return $category->name; }, $product->categories); 
            @endphp
            <tr>
                <td>{{ $i + $meta->from }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ implode(', ', $cats) }}</td>

                <td>
                    <form action="{{ route('product.destroy',$product->id) }}" class="float-start me-1" method="post">
                        <a class="btn btn-primary" href="{{ route('product.edit',$product->id) }}">Edit</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <form action="{{ route('order.store',$product->id) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary">Buy</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p>Showing {{ $meta->from }} to {{ $meta->to }} of {{ $meta->total }} results.</p>
    <div class="d-flex justify-content-between" style="margin-bottom:10rem;">
        @php $prevPage = $page - 1; $nextPage = $page + 1; @endphp
        @if($prevPage >= 1)
        <a href="{{ url()->current() }}?page={{ $prevPage }}" class="btn">« Previous</a>
        @else
        <span class="btn disabled">« Previous</span>
        @endif
        @if($nextPage <= $meta->last_page)
        <a href="{{ url()->current() }}?page={{ $nextPage }}" class="btn">Next »</a>
        @else
        <span class="btn disabled">Next »</span>
        @endif        
    </div>
</div>
@endsection
