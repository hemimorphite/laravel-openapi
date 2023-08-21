@extends('layout')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12">
            <h4>My Shopping Carts</h4>
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
                <th>Transaction ID</th>
                <th>Item Name</th>
                <th>Item Price</th>
                <th>Item Action</th>
                <th>Total</th>
                <th style="width:15rem;">Action</th>
            </tr>
        </thead>
        <tbody>
            
            @php $first = true; @endphp
            @if(isset($order->items))
            @foreach ($order->items as $j => $item)
            <tr>
                @if($first)
                <td rowspan="{{ count($order->items) }}">{{ $order->id }}</td>
                @endif
                <td>{{ $item->name }}</td>
                <td>{{ $item->price }}</td>
                @if($order->status == 'unpaid')
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
                <td rowspan="{{ count($order->items) }}">{{ $order->total }}</td>
                <td rowspan="{{ count($order->items) }}">
                    <form action="{{ route('order.destroy',$order->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
                @endif
            </tr>
            @php $first = false; @endphp
            @endforeach
            @endif
        </tbody>
    </table>
    @if(isset($order->items))
    <div id="paypal"></div>
    @endif
</div>
@endsection

@section('javascript')
paypal.Buttons({
    style: {
        layout: 'horizontal'
    },
    
    createOrder: function(data, actions) {
    return fetch('/api/v1/paypal/order/create', {
        method: 'POST'
    }).then(function(res) {
        //res.json();
        return res.json();
    }).then(function(order) {
        
    });
    },
    onApprove: function(data, actions) {
        return fetch('/api/v1/paypal/order/capture' , {
            method: 'POST'
        }).then(function(res) {
            // console.log(res.json());
            return res.json();
        }).then(function(order) {
            
        });
    }
}).render('#paypal');
@endsection

