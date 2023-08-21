@extends('layout')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-2">
                <h4>Add Product</h4>
            </div>
        </div>
    </div>
    
    <form action="{{ route('product.store') }}" class="col-12 col-lg-6" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                @if(isset($errors) && isset($errors->name))
                <div class="alert alert-danger mt-1 mb-1">{{ $errors->name[0] }}</div>
                @endif
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Product Price</label>
                <input type="text" class="form-control" id="price" name="price" value="{{ old('price') }}">
                @if(isset($errors) && isset($errors->price))
                <div class="alert alert-danger mt-1 mb-1">{{ $errors->price[0] }}</div>
                @endif
            </div>
            
            <div id="categories">
                @if(old('categories') == null)
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <div class="input-group">    
                        <select class="form-select" id="category1" name="categories[0]">
                            <option value="" selected>Choose...</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="button" data-index="1" onclick="add(this)">Add</button>
                    </div>
                </div>
                @else
                    @php $isFirst = true; $index = key(array_slice(old('categories'), -1, 1, true)); @endphp
                    @foreach(old('categories') as $i => $cat)
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <div class="input-group">    
                            <select class="form-select" id="category{{ $i }}" name="categories[{{ $i }}]">
                                <option value="" @if($cat == null) selected @endif>Choose...</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if($cat == $category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @if($isFirst)
                            <button class="btn btn-primary" type="button" data-index="{{ $index }}" onclick="add(this)">Add</button>
                            @php $isFirst = false; @endphp
                            @else
                            <button class="btn btn-danger" type="button" onclick="remmove(this)">Remove</button>
                            @endif
                        </div>
                        @if(isset($errors) && isset($errors->{'categories.'.$i.'.id'}))
                        <div class="alert alert-danger mt-1 mb-1">{{ $errors->{'categories.'.$i.'.id'}[0] }}</div>
                        @endif
                    </div>
                    @endforeach
                @endif
            </div>
            <button type="submit" class="btn btn-primary ml-3">Submit</button>
        </div>
    </form>
</div>
@endsection


@section('javascript')
const selectCategory = '<div class="mb-3">' +
            '<div class="input-group">' +
                '<select class="form-select" id="category_index_" name="categories[_index_]">' +
                    '<option value="" selected>Choose...</option>' +
                    @foreach ($categories as $category)
                    '<option value="{{ $category->id }}">{{ $category->name }}</option>' +
                    @endforeach
                '</select>' +
                '<button class="btn btn-danger" type="button" onclick="remove(this)">remove</button>' +
            '</div>' +
        '</div>';


function add(event) {
    let index = parseInt(event.dataset.index) + 1;
    event.dataset.index = index;
    let html = selectCategory.replaceAll('_index_', index);

    document.querySelector("#categories").insertAdjacentHTML( 'beforeend', html );
}

function remove(event) {
    event.parentElement.parentElement.remove();
}
@endsection


