{{-- filepath: resources/views/usuarios/index.blade.php --}}
@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <h2>Usuarios</h2>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary mb-3">Agregar Usuario</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulario de búsqueda --}}
        <form method="GET" action="{{ route('usuarios.index') }}" class="mb-3 d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Buscar usuario..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Activo</th>
                    <th>Roles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->apellido }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->telefono }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>{{ $usuario->activo ? 'Sí' : 'No' }}</td>
                        <td>
                            @foreach ($usuario->roles as $rol)
                                <span class="badge bg-info">{{ $rol->nombre }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST"
                                style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No se han encontrado resultados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center">
            {{ $usuarios->links() }}
        </div>
    </div>
@endsection
