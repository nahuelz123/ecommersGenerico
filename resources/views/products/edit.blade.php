@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2>Editar Producto</h2>

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nombre del producto</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            {{-- Descripción --}}
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- Precio --}}
            <div class="mb-3">
                <label for="price" class="form-label">Precio</label>
                <input type="number" step="0.01" name="price" class="form-control"
                    value="{{ old('price', $product->price) }}" required>
            </div>

            {{-- Stock --}}
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}"
                    required>
            </div>

            {{-- Categoría --}}
            <div class="mb-3">
                <label for="category_id" class="form-label">Categoría</label>
                <select name="category_id" class="form-select" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Marca --}}
            <div class="mb-3">
                <label for="brand_id" class="form-label">Marca</label>
                <select name="brand_id" class="form-select" required>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}"
                            {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Color --}}
            <div class="mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="text" name="color" class="form-control" value="{{ old('color', $product->color) }}">
            </div>

            {{-- Talle --}}
            <div class="mb-3">
                <label for="size" class="form-label">Talle</label>
                <input type="text" name="size" class="form-control" value="{{ old('size', $product->size) }}">
            </div>

            {{-- Imagen principal --}}
            <div class="mb-3">
                <label class="form-label">Imagen principal actual:</label><br>
                <img src="{{ asset('storage/' . $product->main_image) }}" alt="Imagen" width="150"><br><br>
                <label for="main_image" class="form-label">Cambiar imagen principal</label>
                <input type="file" name="main_image" class="form-control">
            </div>

            {{-- Galería de imágenes --}}
            <div class="mb-3">
                <label class="form-label">Galería actual:</label>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach ($product->images as $image)
                        <div>
                            <img src="{{ asset('storage/' . $image->image_path) }}" width="100" class="img-thumbnail">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label for="images[]" class="form-label">Agregar más imágenes a la galería</label>
                <input type="file" name="images[]" class="form-control" multiple>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar producto</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
