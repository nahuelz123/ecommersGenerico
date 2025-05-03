@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
    <div class="container">
        <h1 class="mb-4">Carrito de Compras</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(count($cart) > 0)
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger mb-3">Vaciar Carrito</button>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $item)
                        <tr>
                            <td>{{ $item['product']->name }}</td>
                            <td>${{ number_format($item['product']->price, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.update', $item['product']->id) }}" method="POST">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control" style="width: 70px;">
                                    <button type="submit" class="btn btn-primary mt-2">Actualizar</button>
                                </form>
                            </td>
                            <td>${{ number_format($item['product']->price * $item['quantity'], 2) }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-end">
                <strong>Total: ${{ number_format(array_reduce($cart, function ($carry, $item) { return $carry + ($item['product']->price * $item['quantity']); }, 0), 2) }}</strong>
            </div>

            <a href="{{ route('checkout.index') }}" class="btn btn-success mt-3">Ir a pagar</a>
        @else
            <p>No hay productos en tu carrito.</p>
        @endif
    </div>
@endsection
