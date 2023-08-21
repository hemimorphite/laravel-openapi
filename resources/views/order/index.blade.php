@extends('layout')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12">
            <h4>Transaction Table</h4>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th style="width:3rem;">No</th>
                <th>Transaction ID</th>
                <th>Item Name</th>
                <th>Item Price</th>
                <th>Item Action</th>
                <th>Total</th>
                <th style="width:15rem;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $i => $ord)
            @php $first = true; @endphp
            @foreach ($ord->items as $j => $item)
            <tr>
                @if($first)
                <td rowspan="{{ count($ord->items) }}">{{ $i + $meta->from }}</td>
                <td rowspan="{{ count($ord->items) }}">{{ $ord->id }}</td>
                @endif
                <td>{{ $item->name }}</td>
                <td>{{ $item->price }}</td>
                @if($ord->status == 'unpaid')
                <td>
                    <form action="{{ route('order.remove',$item->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
                @else
                <td>&nbsp;</td>
                @endif
                @if($first)
                <td rowspan="{{ count($ord->items) }}">{{ $ord->total }}</td>
                <td rowspan="{{ count($ord->items) }}">
                    <form action="{{ route('order.destroy',$ord->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
                @endif
            </tr>
            @php $first = false; @endphp
            @endforeach
            @endforeach
        </tbody>
    </table>
    <p>Showing {{ $meta->from }} to {{ $meta->to }} of {{ $meta->total }} results.</p>
    <div class="d-flex justify-content-between">
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


