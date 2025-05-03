@extends('layouts.app')

@section('title', 'Checkout - Carrito')

@section('content')
<div class="container">
    <h1>Resumen del carrito</h1>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (count($cart) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                    <tr>
                        <td>{{ $item['product']->name }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>${{ number_format($item['product']->price * $item['quantity'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @include('checkout._form', [
        'addresses' => $addresses,
        'shippingMethods' => $shippingMethods,
        'paymentMethods' => $paymentMethods
    ])
</div>
@endsection