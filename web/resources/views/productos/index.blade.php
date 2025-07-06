@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Productos</h2>
    <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">Agregar Producto</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('productos.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Buscar producto..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Fecha Entrada</th>
                <th>Fecha Salida</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->estado }}</td>
                <td>{{ $producto->fecha_entrada }}</td>
                <td>{{ $producto->fecha_salida }}</td>
                <td>{{ $producto->cantidad }}</td>
                <td>
                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No se han encontrado resultados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $productos->links() }}
    </div>
</div>
@endsection