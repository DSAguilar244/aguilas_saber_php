@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Roles</h2>

    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Agregar Rol</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <input type="text" id="search-roles" class="form-control" placeholder="Buscar por nombre o descripción...">
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="roles-body">
            @forelse($roles as $role)
            <tr>
                <td>{{ $role->nombre }}</td>
                <td>{{ $role->descripcion }}</td>
                <td>
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm w-auto">✏️ Editar</a>
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-auto">🗑️ Eliminar</button>
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

    <div class="d-flex justify-content-center" id="roles-paginacion">
        {{ $roles->links() }}
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

        timer = setTimeout(() => {
            fetch(`/roles/buscar?search=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    paginacion.style.display = query ? 'none' : 'block';

                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No se encontraron roles.</td></tr>';
                        return;
                    }

                    data.forEach(role => {
                        tableBody.innerHTML += `
                            <tr>
                                <td>${role.nombre}</td>
                                <td>${role.descripcion ?? ''}</td>
                                <td>
                                    <a href="/roles/${role.id}/edit" class="btn btn-warning btn-sm w-auto">✏️ Editar</a>
                                    <form method="POST" action="/roles/${role.id}" style="display:inline-block;">
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