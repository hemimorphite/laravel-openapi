
@extends('layout')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-2">
                <h4>Login</h4>
            </div>
        </div>
    </div>

    @if ($message = Session::get('failed'))
    <div class="col-12 col-md-6">
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    </div>
    @endif
    
    @if ($message = Session::get('success'))
    <div class="col-12 col-md-6">
        <div class="alert alert-success">
            {{ $message }}
        </div>
    </div>
    @endif

    <form action="{{ route('user.check') }}" class="col-12 col-md-6" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary ml-3">Submit</button>
        </div>
        
    </form>
</div>
@endsection
