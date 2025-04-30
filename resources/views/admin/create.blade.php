@extends('layouts.app')

@section('title', 'Nuevo Administrador')

@section('content')
    <div class="container">
        <h1>Crear Nuevo Administrador</h1>

        <form action="{{ route('users.createadmin', $user->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="user_id" class="form-label">Categoría</label>
                <select name="user_id" class="form-select" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('user_id', $user->id) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="role" value="admin">

            <button type="submit" class="btn btn-primary">Crear</button>
        </form>
    </div>
