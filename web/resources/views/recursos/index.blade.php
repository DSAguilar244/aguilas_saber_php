{{-- resources/views/recursos/index.blade.php --}}
@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/recurso.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <h2>Recursos</h2>
        <a href="{{ route('recursos.create') }}" class="btn btn-primary mb-3">Agregar Recurso</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulario de búsqueda --}}
        <form method="GET" action="{{ route('recursos.index') }}" class="mb-3 d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Buscar recurso..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recursos as $recurso)
                    <tr>
                        <td>{{ $recurso->nombre }}</td>
                        <td>{{ $recurso->descripcion }}</td>
                        <td>{{ $recurso->estado }}</td>
                        <td>
                            <a href="{{ route('recursos.edit', $recurso) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('recursos.destroy', $recurso) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No se han encontrado resultados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center">
            {{ $recursos->links() }}
        </div>
    </div>
@endsection