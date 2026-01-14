@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Productos</h2>

    @php
        $isAdmin = auth()->user()->roles->contains('name', 'admin');
        $canCreate = $isAdmin || auth()->user()->hasPermissionTo('productos.crear');
        $canEdit = $isAdmin || auth()->user()->hasPermissionTo('productos.editar');
        $canDelete = $isAdmin || auth()->user()->hasPermissionTo('productos.eliminar');
    @endphp

    @if($canCreate)
    <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">Agregar Producto</a>
    @endif

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 🔍 Buscador en tiempo real --}}
    <div class="mb-3">
        <input type="text" id="search-productos" class="form-control" placeholder="Buscar por nombre o estado..." autocomplete="off">
    </div>

    {{-- 📋 Tabla de productos --}}
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Fecha Entrada</th>
                <th>Fecha Salida</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="productos-body">
            @forelse($productos as $producto)
            <tr>
                <td data-label="Nombre">{{ $producto->nombre }}</td>
                <td data-label="Estado">
                    @if (strtolower($producto->estado) === 'disponible')
                        <span class="badge badge-disponible">Disponible</span>
                    @else
                        <span class="badge badge-no-disponible">Agotado</span>
                    @endif
                </td>
                <td data-label="Fecha Entrada">{{ $producto->fecha_entrada }}</td>
                <td data-label="Fecha Salida">{{ $producto->fecha_salida }}</td>
                <td data-label="Cantidad">{{ $producto->cantidad }}</td>
                <td data-label="Acciones">
                    @if($canEdit && strtolower($producto->estado) === 'disponible')
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm w-auto"><i class="fas fa-edit"></i> Editar</a>
                    @elseif(strtolower($producto->estado) !== 'disponible')
                        <button class="btn btn-secondary btn-sm w-auto" disabled><i class="fas fa-ban"></i> No editable</button>
                    @endif
                    
                    @if($canDelete)
                    <button type="button" class="btn btn-danger btn-sm w-auto" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ $producto->id }}">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                    @endif
                    
                    {{-- Modal de confirmación --}}
                    <div class="modal fade" id="modalEliminar{{ $producto->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center py-4">
                                    <i class="fas fa-shopping-cart fa-4x text-danger mb-3"></i>
                                    <h5 class="mb-3">¿Estás seguro de eliminar este producto?</h5>
                                    <p class="text-muted mb-0">
                                        <strong>{{ $producto->nombre }}</strong><br>
                                        <small>Cantidad: {{ $producto->cantidad }} | Estado: {{ $producto->estado }}</small>
                                    </p>
                                    <p class="text-danger mt-2"><small>Esta acción no se puede deshacer.</small></p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>Cancelar
                                    </button>
                                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline">
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
                <td colspan="6" class="text-center text-muted">No se han encontrado resultados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 📄 Paginación --}}
    <div class="d-flex justify-content-center" id="productos-paginacion">
        {{ $productos->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-productos');
    const tableBody = document.getElementById('productos-body');
    const paginacion = document.getElementById('productos-paginacion');
    let timer = null;

    input.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(timer);

        paginacion.style.display = query ? 'none' : 'block';

        timer = setTimeout(() => {
            fetch(`/productos/buscar?search=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';

                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No se encontraron productos.</td></tr>';
                        return;
                    }

                    data.forEach(prod => {
                        const badge = prod.estado.toLowerCase() === 'disponible'
                            ? '<span class="badge badge-disponible">Disponible</span>'
                            : '<span class="badge badge-no-disponible">Agotado</span>';

                        const botonActivo = prod.estado.toLowerCase() === 'disponible'
                            ? `<a href="/productos/${prod.id}/edit" class="btn btn-warning btn-sm w-auto"><i class="fas fa-edit"></i> Editar</a>`
                            : `<button class="btn btn-secondary btn-sm w-auto" disabled><i class="fas fa-ban"></i> No editable</button>`;

                        tableBody.innerHTML += `
                            <tr>
                                <td data-label="Nombre">${prod.nombre}</td>
                                <td data-label="Estado">${badge}</td>
                                <td data-label="Fecha Entrada">${prod.fecha_entrada ?? ''}</td>
                                <td data-label="Fecha Salida">${prod.fecha_salida ?? ''}</td>
                                <td data-label="Cantidad">${prod.cantidad}</td>
                                <td data-label="Acciones">
                                    ${botonActivo}
                                    <form method="POST" action="/productos/${prod.id}" style="display:inline-block;">
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