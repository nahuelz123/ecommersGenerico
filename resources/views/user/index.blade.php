@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="container">
    <h1>Mi Perfil</h1>

    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>Nombre:</strong> {{ $user->name }}</li>
        <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
        <li class="list-group-item"><strong>Rol:</strong> {{ $user->role }}</li>
    </ul>

    <a href="{{ route('user.edit') }}" class="btn btn-primary">Editar mis datos</a>
</div>
@endsection