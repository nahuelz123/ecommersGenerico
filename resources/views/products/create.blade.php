@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2>Crear Producto</h2>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nombre --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nombre del producto</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            {{-- Descripción --}}
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>

            {{-- Precio --}}
            <div class="mb-3">
                <label for="price" class="form-label">Precio</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            {{-- Stock --}}
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>

            {{-- Categoría --}}
            <div class="mb-3">
                <label for="category_id" class="form-label">Categoría</label>
                <select name="category_id" class="form-select" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Marca --}}
            <div class="mb-3">
                <label for="brand_id" class="form-label">Marca</label>
                <select name="brand_id" class="form-select" required>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Color --}}
            <div class="mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="text" name="color" class="form-control">
            </div>

            {{-- Talle --}}
            <div class="mb-3">
                <label for="size" class="form-label">Talle</label>
                <input type="text" name="size" class="form-control">
            </div>

            {{-- Imagen principal --}}
            <div class="mb-3">
                <label for="main_image" class="form-label">Imagen principal</label>
                <input type="file" name="main_image" class="form-control">
            </div>

            {{-- Galería --}}
            <div class="mb-3">
                <label for="images[]" class="form-label">Galería de imágenes (opcional)</label>
                <input type="file" name="images[]" class="form-control" multiple>
            </div>

            <button type="submit" class="btn btn-primary">Crear producto</button>
        </form>
    </div>
@endsection
