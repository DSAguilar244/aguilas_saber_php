@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Recursos</h2>

    <a href="{{ route('recursos.create') }}" class="btn btn-primary mb-3">Agregar Recurso</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 🔍 Búsqueda en tiempo real --}}
    <div class="mb-3">
        <input type="text" id="search-input" class="form-control" placeholder="Buscar recurso...">
    </div>

    {{-- 📋 Tabla de recursos --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="recursos-body">
            @forelse ($recursos as $recurso)
                <tr>
                    <td>{{ $recurso->nombre }}</td>
                    <td>{{ $recurso->descripcion }}</td>
                    <td>
                        @if ($recurso->estado === 'Disponible')
                            <span class="badge badge-disponible">Disponible</span>
                        @else
                            <span class="badge badge-no-disponible">{{ $recurso->estado }}</span>
                        @endif
                    </td>
                    <td>{{ $recurso->cantidad }}</td>
                    <td>
                        <a href="{{ route('recursos.edit', $recurso) }}" class="btn btn-warning btn-sm w-auto">✏️ Editar</a>
                        <form action="{{ route('recursos.destroy', $recurso) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-auto">🗑️ Eliminar</button>
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

    <div class="d-flex justify-content-center">
        {{ $recursos->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const recursosBody = document.getElementById('recursos-body');
    let timer = null;

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();

        clearTimeout(timer);
        timer = setTimeout(() => {
            fetch(`/recursos/buscar?search=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    recursosBody.innerHTML = '';

                    if (data.length === 0) {
                        recursosBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No se encontraron recursos.</td></tr>';
                        return;
                    }

                    data.forEach(recurso => {
                        recursosBody.innerHTML += `
                            <tr>
                                <td>${recurso.nombre}</td>
                                <td>${recurso.descripcion}</td>
                                <td>
                                    <span class="badge ${recurso.estado === 'Disponible' ? 'badge-disponible' : 'badge-no-disponible'}">${recurso.estado}</span>
                                </td>
                                <td>${recurso.cantidad}</td>
                                <td>
                                    <a href="/recursos/${recurso.id}/edit" class="btn btn-warning btn-sm w-auto">✏️ Editar</a>
                                    <form method="POST" action="/recursos/${recurso.id}" style="display:inline-block;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm w-auto">🗑️ Eliminar</button>
                                    </form>
                                </td>
                            </tr>`;
                    });
                });
        }, 500);
    });
});
</script>
@endsection