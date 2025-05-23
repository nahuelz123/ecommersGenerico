@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2>{{ $product->name }}</h2>

        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('storage/' . $product->main_image) }}" class="img-fluid mb-3" alt="Imagen principal">
                <div class="d-flex gap-2 flex-wrap">
                    @foreach ($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" width="100" height="100"
                            class="img-thumbnail">
                    @endforeach
                </div>
            </div>

            <div class="col-md-6">
                <p><strong>Descripción:</strong> {{ $product->description }}</p>
                <p><strong>Precio:</strong> ${{ $product->price }}</p>
                <p><strong>Stock:</strong> {{ $product->stock }}</p>
                <p><strong>Categoría:</strong> {{ $product->category->name }}</p>
                <p><strong>Marca:</strong> {{ $product->brand->name }}</p>
                <p><strong>Color:</strong> {{ $product->color }}</p>
                <p><strong>Talle:</strong> {{ $product->size }}</p>

                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger"
                                onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</button>
                        </form>
                    @else
                        <!-- Cliente autenticado -->
                        <form action="{{ route('cart.addToCart', $product->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-primary">Agregar al carrito</button>
                        </form>
                        <form action="{{ route('checkout.directPurchase', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">Comprar ahora</button>
                        </form>
                    @endif
                @else
                    <!-- Visitante no autenticado -->
                    <a href="{{ route('login') }}" class="btn btn-primary mb-2">Agregar al carrito</a>
                    <a href="{{ route('login') }}" class="btn btn-success">Comprar ahora</a>
                @endauth
            </div>
        </div>
    </div>
@endsection
