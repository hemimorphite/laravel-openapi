@extends('layout')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12">
            <h4>Category Table</h4>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
    @if ($message = Session::get('failed'))
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width:3rem;">No</th>
                <th>Category Name</th>
                <th style="width:15rem;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $i => $category)
            <tr>
                <td>{{ $i + $meta->from }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <form action="{{ route('category.destroy',$category->id) }}" method="post">
                        <a class="btn btn-primary" href="{{ route('category.edit',$category->id) }}">Edit</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p>Showing {{ $meta->from }} to {{ $meta->to }} of {{ $meta->total }} results.</p>
    <div class="d-flex justify-content-between" style="margin-bottom:10rem;>
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
