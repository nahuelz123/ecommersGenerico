@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-4">
        <h2 class="text-xl font-bold mb-4">Carrito de compras</h2>

        @if (empty($cart))
            <p>No hay productos en el carrito.</p>
        @else
            <table class="w-full text-left border">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($cart as $item)
                        @php
                            $subtotal = $item['product']->price * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr class="border-t">
                            <td>{{ $item['product']->name }}</td>
                            <td>${{ $item['product']->price }}</td>
                            <td>
                                <form method="POST" action="{{ route('cart.update', $item['product']) }}">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1">
                                    <button type="submit" class="text-blue-600 ml-2">Actualizar</button>
                                </form>
                            </td>
                            <td>${{ $subtotal }}</td>
                            <td>
                                <form method="POST" action="{{ route('cart.remove', $item['product']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <p class="text-lg font-semibold">Total: ${{ $total }}</p>

                <form action="{{ route('cart.clear') }}" method="POST" class="mt-2 inline-block">
                    @csrf
                    <button class="bg-red-500 px-4 py-2 text-white rounded">Vaciar carrito</button>
                </form>

                <a href="{{ route('checkout.index') }}" class="ml-4 bg-green-500 px-4 py-2 text-white rounded">Finalizar compra</a>
            </div>
        @endif
    </div>
@endsection
