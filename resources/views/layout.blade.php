<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        
        <script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.sandbox.client_id') }}&currency=USD"></script>
        
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Alba Digital</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        @guest
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('register') }}"><b>Register</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('login') }}"><b>login</b></a>
                        </li>
                        @endguest
                        @auth
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('category.index') }}"><b>Category Table</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('category.create') }}"><b>Create Category</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('product.index') }}"><b>Product Table</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('product.create') }}"><b>Create Product</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('order.show') }}"><b>Shopping Carts <span id="cartCounter">@if(isset($order->items))({{ count($order->items) }})@endif</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('order.index') }}"><b>Transaction Table</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('logout') }}"><b>Logout</b></a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script>
            @yield('javascript')
        </script>
    </body>
</html>
