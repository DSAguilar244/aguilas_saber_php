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

    {{-- 🔍 Buscador con mejor usabilidad --}}
    <div class="mb-3 position-relative">
        <label for="search-usuarios" class="form-label">Buscar por nombre, apellido o correo</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="search-usuarios" class="form-control"
                   placeholder="Ej: Andrea, López, ejemplo@mail.com" autocomplete="off">
        </div>
        <small class="form-text text-muted">La búsqueda se actualiza automáticamente mientras escribes.</small>
    </div>

    {{-- 📋 Tabla de usuarios --}}
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
        <tbody id="usuarios-body">
            @forelse ($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->nombre }}</td>
                <td>{{ $usuario->apellido }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $usuario->telefono }}</td>
                <td>{{ $usuario->rol }}</td>
                <td>{{ $usuario->activo ? 'Sí' : 'No' }}</td>
                <td>
                    @forelse ($usuario->roles as $rol)
                    <span class="badge bg-info">{{ $rol->nombre }}</span>
                    @empty
                    <span class="badge badge-no-disponible">Sin rol</span>
                    @endforelse
                </td>
                <td>
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm w-auto">✏️ Editar</a>
                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-auto">🗑️ Eliminar</button>
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

    {{-- 📄 Paginación --}}
    <div class="d-flex justify-content-center" id="usuarios-paginacion">
        {{ $usuarios->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-usuarios');
    const tableBody = document.getElementById('usuarios-body');
    const paginacion = document.getElementById('usuarios-paginacion');
    let timer = null;

    input.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(timer);

        paginacion.style.display = query ? 'none' : 'block';

        timer = setTimeout(() => {
            fetch(`/usuarios/buscar?search=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';

                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No se encontraron usuarios.</td></tr>';
                        return;
                    }

                    data.forEach(usuario => {
                        const rolesHTML = usuario.roles.length > 0
                            ? usuario.roles.map(r => `<span class="badge bg-info">${r.nombre}</span>`).join(' ')
                            : `<span class="badge badge-no-disponible">Sin rol</span>`;

                        tableBody.innerHTML += `
                            <tr>
                                <td>${usuario.nombre}</td>
                                <td>${usuario.apellido}</td>
                                <td>${usuario.email}</td>
                                <td>${usuario.telefono}</td>
                                <td>${usuario.rol}</td>
                                <td>${usuario.activo ? 'Sí' : 'No'}</td>
                                <td>${rolesHTML}</td>
                                <td>
                                    <a href="/usuarios/${usuario.id}/edit" class="btn btn-warning btn-sm w-auto">✏️ Editar</a>
                                    <form method="POST" action="/usuarios/${usuario.id}" style="display:inline-block;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm w-auto">🗑️ Eliminar</button>
                                    </form>
                                </td>
                            </tr>`;
                    });
                });
        }, 400);
    });
});
</script>
@endsection