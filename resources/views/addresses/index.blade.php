@extends('layouts.app')

@section('title', 'Mis Direcciones')

@section('content')
<div class="container">
    <h1 class="mb-4">Mis Direcciones de Envío</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('addresses.create') }}" class="btn btn-primary mb-4">Agregar Dirección</a>

    @forelse($addresses as $address)
        <div class="card mb-3">
            <div class="card-body">
                <p>{{ $address->street }}, {{ $address->city->name }}, {{ $address->province->name }}</p>
                <a href="{{ route('addresses.edit', $address) }}" class="btn btn-warning">Editar</a>
                <form action="{{ route('addresses.destroy', $address) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    @empty
        <p>No tienes direcciones de envío registradas.</p>
    @endforelse
</div>
@endsection
