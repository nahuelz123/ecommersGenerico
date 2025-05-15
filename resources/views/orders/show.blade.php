@extends('layouts.app')
@section('title', 'Orden #' . $order->id)

@section('content')
<div class="container">
    <h1>Orden #{{ $order->id }}</h1>
    <p><strong>Estado:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
    <p><strong>Dirección:</strong> {{ $order->address->address }} - {{ $order->address->city->name }}</p>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
