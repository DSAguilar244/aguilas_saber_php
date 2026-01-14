@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Recursos</h2>

    @php
        $isAdmin = auth()->user()->roles->contains('name', 'admin');
        $canCreate = $isAdmin || auth()->user()->hasPermissionTo('recursos.crear');
        $canEdit = $isAdmin || auth()->user()->hasPermissionTo('recursos.editar');
        $canDelete = $isAdmin || auth()->user()->hasPermissionTo('recursos.eliminar');
    @endphp

    @if($canCreate)
    <a href="{{ route('recursos.create') }}" class="btn btn-primary mb-3">Agregar Recurso</a>
    @endif

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 🔍 Buscador sin ícono --}}
    <div class="mb-3">
        <input type="text" id="search-recursos" class="form-control" placeholder="Buscar recurso por nombre, estado o descripción..." autocomplete="off">
    </div>

    {{-- 📋 Tabla de recursos --}}
    <table class="table table-bordered">
        <thead class="table-dark">
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
                <td data-label="Nombre">{{ $recurso->nombre }}</td>
                <td data-label="Descripción">{{ $recurso->descripcion }}</td>
                <td data-label="Estado">
                    @if ($recurso->estado === 'Disponible')
                        <span class="badge badge-disponible">Disponible</span>
                    @else
                        <span class="badge badge-no-disponible">{{ $recurso->estado }}</span>
                    @endif
                </td>
                <td data-label="Cantidad">{{ $recurso->cantidad }}</td>
                <td data-label="Acciones">
                    @if($canEdit)
                    <a href="{{ route('recursos.edit', $recurso) }}" class="btn btn-warning btn-sm w-auto"><i class="fas fa-edit"></i> Editar</a>
                    @endif
                    
                    @if($canDelete)
                    <button type="button" class="btn btn-danger btn-sm w-auto" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ $recurso->id }}">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                    @endif
                    
                    {{-- Modal de confirmación --}}
                    <div class="modal fade" id="modalEliminar{{ $recurso->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center py-4">
                                    <i class="fas fa-database fa-4x text-danger mb-3"></i>
                                    <h5 class="mb-3">¿Estás seguro de eliminar este recurso?</h5>
                                    <p class="text-muted mb-0">
                                        <strong>{{ $recurso->nombre }}</strong><br>
                                        <small>Cantidad: {{ $recurso->cantidad }} | Estado: {{ $recurso->estado }}</small>
                                    </p>
                                    <p class="text-danger mt-2"><small>Esta acción no se puede deshacer.</small></p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>Cancelar
                                    </button>
                                    <form action="{{ route('recursos.destroy', $recurso) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash me-1"></i>Sí, Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No se han encontrado resultados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 📄 Paginación --}}
    <div class="d-flex justify-content-center" id="recursos-paginacion">
        {{ $recursos->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-recursos');
    const tableBody = document.getElementById('recursos-body');
    const paginacion = document.getElementById('recursos-paginacion');
    let timer = null;

    input.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(timer);

        paginacion.style.display = query ? 'none' : 'block';

        timer = setTimeout(() => {
            fetch(`/recursos/buscar?search=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';

                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No se encontraron recursos.</td></tr>';
                        return;
                    }

                    data.forEach(recurso => {
                        const badge = recurso.estado === 'Disponible'
                            ? '<span class="badge badge-disponible">Disponible</span>'
                            : `<span class="badge badge-no-disponible">${recurso.estado}</span>`;

                        tableBody.innerHTML += `
                            <tr>
                                <td data-label="Nombre">${recurso.nombre}</td>
                                <td data-label="Descripción">${recurso.descripcion ?? ''}</td>
                                <td data-label="Estado">${badge}</td>
                                <td data-label="Cantidad">${recurso.cantidad}</td>
                                <td data-label="Acciones">
                                    <a href="/recursos/${recurso.id}/edit" class="btn btn-warning btn-sm w-auto"><i class="fas fa-edit"></i> Editar</a>
                                    <form method="POST" action="/recursos/${recurso.id}" style="display:inline-block;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm w-auto"><i class="fas fa-trash"></i> Eliminar</button>
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