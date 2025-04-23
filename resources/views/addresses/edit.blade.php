@extends('layouts.app')

@section('title', 'Editar Dirección de Envío')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Dirección de Envío</h1>

    <!-- Mostrar mensaje de error general si existe -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('addresses.update', $address->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Usamos PUT para indicar que es una actualización -->

        <div class="form-group mb-3">
            <label for="phone">Teléfono</label>
            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $address->phone) }}" required>
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="address">Calle y Número</label>
            <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $address->address) }}" required>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="province_id">Provincia</label>
            <select id="province_id" name="province_id" class="form-control @error('province_id') is-invalid @enderror" required>
                <option value="">Selecciona una provincia</option>
                @foreach($provinces as $province)
                    <option value="{{ $province->id }}" {{ old('province_id', $address->province_id) == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                @endforeach
            </select>
            @error('province_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="city_id">Ciudad</label>
            <select id="city_id" name="city_id" class="form-control @error('city_id') is-invalid @enderror" required>
                <option value="">Selecciona una ciudad</option>
            </select>
            @error('city_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="postal_code">Código Postal</label>
            <input type="text" id="postal_code" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code', $address->postal_code) }}" required>
            @error('postal_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <br><br>
        <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary">Actualizar Dirección</button>
        </div>
    </form>

    <a href="{{ route('addresses.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>

<script>
    // Cargar ciudades de la provincia seleccionada
    document.getElementById('province_id').addEventListener('change', function () {
        var provinceId = this.value;

        // Verificamos si se ha seleccionado una provincia
        if (provinceId) {
            // Realizamos la petición AJAX para cargar las ciudades
            fetch(`/get-cities/${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    var citySelect = document.getElementById('city_id');
                    citySelect.innerHTML = '<option value="">Selecciona una ciudad</option>'; // Limpiamos las opciones previas

                    // Agregamos las ciudades de la provincia seleccionada
                    data.cities.forEach(function (city) {
                        var option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });

                    // Inicializamos Select2 para la búsqueda
                    $('#city_id').select2({
                        placeholder: 'Selecciona una ciudad',
                        allowClear: true
                    });
                });
        } else {
            // Si no se selecciona una provincia, limpiamos las ciudades
            document.getElementById('city_id').innerHTML = '<option value="">Selecciona una ciudad</option>';
        }
    });

    // Preseleccionar ciudad si la provincia ya está seleccionada
    document.addEventListener('DOMContentLoaded', function () {
        var selectedProvince = document.getElementById('province_id').value;
        if (selectedProvince) {
            fetch(`/get-cities/${selectedProvince}`)
                .then(response => response.json())
                .then(data => {
                    var citySelect = document.getElementById('city_id');
                    data.cities.forEach(function (city) {
                        var option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        if (city.id == "{{ old('city_id', $address->city_id) }}") {
                            option.selected = true;
                        }
                        citySelect.appendChild(option);
                    });
                });
        }
    });
</script>

@endsection
