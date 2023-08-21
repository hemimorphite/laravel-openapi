@extends('layout')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-2">
                <h4>Add Category</h4>
            </div>
        </div>
    </div>
    
    <form action="{{ route('category.store') }}" class="col-12 col-md-6" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                @if(isset($errors) && isset($errors->name))
                <div class="alert alert-danger mt-1 mb-1">{{ $errors->name[0] }}</div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary ml-3">Submit</button>
        </div>
    </form>
</div>
@endsection
