@extends('layouts.app')

@section('title', 'Todos los productos')

@section('content')
    <div class="container">
        <h1 class="mb-4">Productos</h1>

        {{-- Filtros (opcional) --}}
        <form method="GET" action="{{ route('products.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <select name="brand" class="form-select">
                    <option value="">Todas las marcas</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
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
                        </div>
                    </div>
                </div>
            @empty
                <p>No hay productos disponibles.</p>
            @endforelse
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
@endsection
