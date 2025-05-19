@extends('layouts.app')

@section('title', 'Todos los productos')

@section('content')
<div class="container">
    <h1 class="mb-4 d-flex justify-content-between align-items-center">
        Productos
        @auth
            @if (auth()->user()->role === 'admin')
            <a href="{{ route('admin.products.create') }}" class="btn btn-success">

                    <i class="bi bi-plus-lg"></i> Nuevo producto
                </a>
            @endif
        @endauth
    </h1>

    {{-- Filtros con búsqueda --}}
    <form method="GET" action="{{ route('products.index') }}" class="row g-3 mb-4 align-items-end">
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">Todas las categorías</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="brand" class="form-select">
                <option value="">Todas las marcas</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <div class="input-group">
                <button class="btn btn-outline-secondary" type="button" id="toggleSearch">
                    <img src="{{ asset('storage/image/buscar.png') }}" alt="Buscar" style="width: 20px; height: 20px;">
                </button>

                <input type="text" name="search" class="form-control d-none"
                    placeholder="Buscar por nombre, marca o categoría" value="{{ request('search') }}" id="searchInput">
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toggleSearch = document.getElementById('toggleSearch');
                const searchInput = document.getElementById('searchInput');

                toggleSearch.addEventListener('click', function () {
                    searchInput.classList.toggle('d-none');
                    if (!searchInput.classList.contains('d-none')) {
                        searchInput.focus();
                    }
                });
            });
        </script>

        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit">Filtrar</button>
        </div>
    </form>

    {{-- Lista de productos --}}
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $product->main_image) }}" class="card-img-top" alt="{{ $product->name }}">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted">${{ number_format($product->price, 2) }}</p>

                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary mt-auto">Ver detalles</a>

                        {{-- Botones según el tipo de usuario --}}
                        @if (auth()->check() && auth()->user()->role === 'admin')
                            {{-- Admin --}}
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning mt-2">Editar</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger mt-1"
                                    onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</button>
                            </form>
                        @else
                            {{-- Cliente o visitante --}}
                            @guest
                                {{-- Visitante: redirige al login --}}
                                <button class="btn btn-primary w-100 mt-2" onclick="location.href='{{ route('login') }}'">
                                    Agregar al carrito
                                </button>

                                <div class="mt-2">
                                    <label for="quantity-{{ $product->id }}">Cantidad:</label>
                                    <input type="number" id="quantity-{{ $product->id }}" class="form-control mb-2 w-50" value="1" min="1" disabled>
                                    <button class="btn btn-success w-100" onclick="location.href='{{ route('login') }}'">Comprar ahora</button>
                                </div>
                            @else
                                {{-- Cliente autenticado --}}
                                <form action="{{ route('cart.addToCart', $product->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">Agregar al carrito</button>
                                </form>

                                <form action="{{ route('checkout.directPurchase', $product->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <label for="quantity-{{ $product->id }}">Cantidad:</label>
                                    <input type="number" name="quantity" id="quantity-{{ $product->id }}" class="form-control mb-2 w-50" value="1" min="1" max="{{ $product->stock }}" required>
                                    <button type="submit" class="btn btn-success w-100">Comprar ahora</button>
                                </form>
                            @endguest
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p>No hay productos disponibles.</p>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>

    @auth
        @if (auth()->user()->role !== 'admin')
            <a href="{{ route('checkout.index') }}" class="btn btn-success mt-3">Ir a pagar</a>
        @endif
    @endauth
</div>
@endsection
