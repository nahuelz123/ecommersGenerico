@extends('layouts.app')

@section('title', 'Agregar Dirección de Envío')

@section('content')
<div class="container">
    <h1 class="mb-4">Agregar Dirección de Envío</h1>

    <form action="{{ route('addresses.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="street">Calle y Número</label>
            <input type="text" id="street" name="street" class="form-control @error('street') is-invalid @enderror" value="{{ old('street') }}" required>
            @error('street')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="province_id">Provincia</label>
            <select id="province_id" name="province_id" class="form-control @error('province_id') is-invalid @enderror" required>
                <option value="">Selecciona una provincia</option>
                @foreach($provinces as $province)
                    <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
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
            <button type="submit" class="btn btn-primary">Guardar Dirección</button>
        </div>
    </form>

    <a href="{{ route('addresses.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>

<script>
    // Cuando se selecciona una provincia, cargamos las ciudades correspondientes
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


</script>
@endsection
