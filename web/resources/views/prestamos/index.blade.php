@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/prestamo.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Préstamos</h2>
    <a href="{{ route('prestamos.create') }}" class="btn btn-primary mb-3">Agregar Préstamo</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('prestamos.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Buscar préstamo..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Usuario</th>
                <th>Recurso</th>
                <th>Fecha Préstamo</th>
                <th>Fecha Devolución</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prestamos as $prestamo)
            <tr>
                <td>{{ $prestamo->codigo }}</td>
                <td>{{ $prestamo->usuario->nombre ?? '' }} {{ $prestamo->usuario->apellido ?? '' }}</td>
                <td>{{ $prestamo->recurso->nombre ?? '' }}</td>
                <td>{{ $prestamo->fecha_prestamo }}</td>
                <td>{{ $prestamo->fecha_devolucion }}</td>
                <td>{{ $prestamo->estado }}</td>
                <td>
                    <a href="{{ route('prestamos.edit', $prestamo) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('prestamos.destroy', $prestamo) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">No se han encontrado resultados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $prestamos->links() }}
    </div>
</div>
@endsection