@extends('layouts.app')

@section('title', 'Compra directa')

@section('content')
    <div class="container">
        <h1>Compra directa</h1>
        <h1 class="mb-4">Resumen de compra</h1>

        @php $directPurchase = session('direct_purchase');
            //$product = \App\Models\Product::find($directPurchase['product_id']);
        @endphp

        @if ($directPurchase && session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $directPurchase['quantity'] }}</td>
                        <td>${{ number_format($directPurchase['subtotal'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p class="alert alert-danger">No hay productos en tu carrito.</p>
        @endif
    </div>

    @include('checkout._form', [
        'addresses' => $addresses,
        'shippingMethods' => $shippingMethods,
        'paymentMethods' => $paymentMethods,
    ])
    </div>
@endsection
