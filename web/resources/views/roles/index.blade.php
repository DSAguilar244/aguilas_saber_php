@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Roles del Sistema</h2>
    <p class="text-muted">Gestiona los permisos de los roles existentes</p>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 🔍 Buscador sin ícono --}}
    <div class="mb-3">
        <input type="text" id="search-roles" class="form-control" placeholder="Buscar por nombre o descripción..." autocomplete="off">
    </div>

    {{-- 📋 Tabla de roles --}}
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="roles-body">
            @forelse($roles as $role)
            <tr>
                <td data-label="Nombre">{{ $role->name }}</td> {{-- ✅ Usar 'name' --}}
                <td data-label="Descripción">{{ $role->descripcion }}</td>
                <td data-label="Acciones">
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm w-auto">
                        <i class="fas fa-edit"></i> Editar Permisos
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-muted">No se han encontrado resultados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 📄 Paginación --}}
    <div class="d-flex justify-content-center" id="roles-paginacion">
        {{ $roles->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-roles');
    const tableBody = document.getElementById('roles-body');
    const paginacion = document.getElementById('roles-paginacion');
    let timer = null;

    input.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(timer);

        paginacion.style.display = query ? 'none' : 'block';

        timer = setTimeout(() => {
            fetch(`/roles/buscar?search=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';

                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No se encontraron roles.</td></tr>';
                        return;
                    }

                    data.forEach(role => {
                        tableBody.innerHTML += `
                            <tr>
                                <td data-label="Nombre">${role.name}</td> <!-- ✅ corregido -->
                                <td data-label="Descripción">${role.descripcion ?? ''}</td>
                                <td data-label="Acciones">
                                    <a href="/roles/${role.id}/edit" class="btn btn-warning btn-sm w-auto"><i class="fas fa-edit"></i> Editar</a>
                                    <form method="POST" action="/roles/${role.id}" style="display:inline-block;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm w-auto"><i class="fas fa-trash"></i> Eliminar</button>
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