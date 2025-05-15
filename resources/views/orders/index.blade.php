@extends('layouts.app')
@section('title', 'Mis Órdenes')

@section('content')
<div class="container">
    <h1>Mis Órdenes</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th><th>Estado</th><th>Total</th><th>Fecha</th><th></th>
            </tr>
        </thead>
        <tbody>
            @if ($orders->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">No tienes órdenes.</td>
                </tr>
                
            @else
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>${{ number_format($order->total, 2) }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td><a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">Ver</a></td>
            </tr>
        @endforeach
            @endif
            
        </tbody>
    </table>
</div>
@endsection
