@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <h1 class="mb-4">Resumen de compra</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($cart) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $item)
                    @php 
                        $subtotal = $item['product']->price * $item['quantity'];
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $item['product']->name }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>${{ number_format($subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end">
            <h4>Total: ${{ number_format($total, 2) }}</h4>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" class="mt-4">
            @csrf

            {{-- Dirección de envío --}}
            <div class="mb-3">
                <label for="address_id" class="form-label">Dirección de envío</label>
                @if($addresses && count($addresses) > 0)
                    <select name="address_id" id="address_id" class="form-control" required>
                        @foreach($addresses as $address)
                            <option value="{{ $address->id }}">
                                {{$address->address }} - {{ $address->city->name }}
                            </option>
                        @endforeach
                        
                    </select>
                    <br>
                    <a href="{{ route('addresses.create') }}" class="btn btn-sm btn-outline-primary">Agregar otra dirección</a>
                @else
                    <p>No tenés direcciones registradas. 
                        <a href="{{ route('addresses.create') }}" class="btn btn-sm btn-outline-primary">Agregar dirección</a>
                    </p>
                @endif
            </div>

            {{-- Método de envío --}}
            <div class="mb-3">
                <label for="shipping_method_id" class="form-label">Método de envío</label>
                <select name="shipping_method_id" id="shipping_method_id" class="form-control" required>
                    @foreach($shippingMethods as $method)
                        <option value="{{ $method->id }}">
                            {{ $method->name }} - ${{ number_format($method->price, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Método de pago --}}
            <div class="mb-3">
                <label for="payment_method_id" class="form-label">Método de pago</label>
                <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Confirmar compra</button>
        </form>
    @else
        <p>No hay productos en tu carrito.</p>
    @endif
</div>
@endsection
