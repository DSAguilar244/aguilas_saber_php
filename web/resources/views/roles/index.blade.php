@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/recurso.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Roles</h2>
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Agregar Rol</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('roles.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Buscar rol..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
            <tr>
                <td>{{ $role->nombre }}</td>
                <td>{{ $role->descripcion }}</td>
                <td>
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-muted">No se han encontrado resultados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $roles->links() }}
    </div>
</div>
@endsection