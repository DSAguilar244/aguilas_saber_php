@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Registrar Préstamo</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('prestamos.store') }}" method="POST" novalidate>
        @csrf

        <div class="mb-3">
            <label for="codigo">Código <span class="text-danger">*</span></label>
            <input type="text" name="codigo" id="codigo" class="form-control"
                   value="{{ old('codigo') }}" required maxlength="50"
                   placeholder="Ej: PREST-001">
            <small class="form-text text-muted">Identificador único del préstamo.</small>
        </div>

        <div class="mb-3">
            <label for="usuario_id">Usuario <span class="text-danger">*</span></label>
            <select name="usuario_id" id="usuario_id" class="form-control" required>
                <option value="">Seleccione un usuario</option>
                @foreach($usuarios as $usuario)
                <option value="{{ $usuario->id }}" {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                    {{ $usuario->nombre }} {{ $usuario->apellido }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="recurso_id">Recurso <span class="text-danger">*</span></label>
            <select name="recurso_id" id="recurso_id" class="form-control" required>
                <option value="">Seleccione un recurso</option>
                @foreach($recursos as $recurso)
                <option value="{{ $recurso->id }}" {{ old('recurso_id') == $recurso->id ? 'selected' : '' }}>
                    {{ $recurso->nombre }}
                </option>
                @endforeach
            </select>
            <div id="recurso-error" class="text-danger small mt-1" style="display: none;"></div>
        </div>

        <div class="mb-3">
            <label for="fecha_prestamo">Fecha Préstamo <span class="text-danger">*</span></label>
            <input type="date" name="fecha_prestamo" id="fecha_prestamo" class="form-control"
                   value="{{ old('fecha_prestamo') }}" required>
        </div>

        <div class="mb-3">
            <label for="fecha_devolucion">Fecha Devolución</label>
            <input type="date" name="fecha_devolucion" id="fecha_devolucion" class="form-control"
                   value="{{ old('fecha_devolucion') }}">
        </div>

        <div class="mb-3">
            <label for="estado">Estado <span class="text-danger">*</span></label>
            <select name="estado" id="estado" class="form-control" required>
                <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="devuelto" {{ old('estado') == 'devuelto' ? 'selected' : '' }}>Devuelto</option>
                <option value="no devuelto" {{ old('estado') == 'no devuelto' ? 'selected' : '' }}>No devuelto</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success" id="guardar-btn">Guardar</button>
        <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

{{-- Modal de información --}}
<div class="modal fade" id="modalInfoDevolucion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Información</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-calendar-check fa-4x text-warning mb-3"></i>
                <h5 class="mb-3">Estado actualizado automáticamente</h5>
                <p class="text-muted mb-0">
                    Si ingresas una fecha de devolución, el estado debe ser <strong>"Devuelto"</strong>.
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="fas fa-check me-1"></i>Entendido
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const codigoInput = document.getElementById('codigo');
    const usuarioSelect = document.getElementById('usuario_id');
    const recursoSelect = document.getElementById('recurso_id');
    const fechaPrestamoInput = document.getElementById('fecha_prestamo');
    const estadoSelect = document.getElementById('estado');
    const errorDiv = document.getElementById('recurso-error');
    const guardarBtn = document.getElementById('guardar-btn');
    const form = document.querySelector('form');
    let timer = null;

    function validarFormulario() {
        const codigo = codigoInput.value.trim();
        const usuario = usuarioSelect.value;
        const recurso = recursoSelect.value;
        const fechaPrestamo = fechaPrestamoInput.value;
        const estado = estadoSelect.value;

        const todosCompletos = codigo && usuario && recurso && fechaPrestamo && estado;
        guardarBtn.disabled = !todosCompletos;
    }

    recursoSelect.addEventListener('change', function () {
        const recursoId = this.value;
        errorDiv.style.display = 'none';
        validarFormulario();

        if (!recursoId) return;

        clearTimeout(timer);
        timer = setTimeout(() => {
            fetch("/recursos/validar-disponibilidad", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ recurso_id: recursoId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.activo) {
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>⚠️ Este recurso ya está en préstamo activo.';
                    errorDiv.style.display = 'block';
                    guardarBtn.disabled = true;
                } else {
                    errorDiv.style.display = 'none';
                    validarFormulario();
                }
            });
        }, 400);
    });

    // Validar si hay fecha de devolución, debe ser estado "devuelto"
    const fechaDevolucionInput = document.getElementById('fecha_devolucion');
    
    function validarFechaDevolucion() {
        const fechaDevolucion = fechaDevolucionInput.value;
        const estado = estadoSelect.value;

        if (fechaDevolucion && estado !== 'devuelto') {
            estadoSelect.value = 'devuelto';
            const modal = new bootstrap.Modal(document.getElementById('modalInfoDevolucion'));
            modal.show();
        }
    }

    fechaDevolucionInput.addEventListener('change', validarFechaDevolucion);

    [codigoInput, usuarioSelect, fechaPrestamoInput, estadoSelect].forEach(input => {
        input.addEventListener('change', validarFormulario);
        input.addEventListener('input', validarFormulario);
    });

    form.addEventListener('submit', function (e) {
        const codigo = codigoInput.value.trim();
        const usuario = usuarioSelect.value;
        const recurso = recursoSelect.value;
        const fechaPrestamo = fechaPrestamoInput.value;
        const fechaDevolucion = fechaDevolucionInput.value;
        const estado = estadoSelect.value;

        if (!codigo) { e.preventDefault(); alert('⚠️ Por favor, completa el campo Código'); codigoInput.focus(); return false; }
        if (!usuario) { e.preventDefault(); alert('⚠️ Por favor, selecciona un Usuario'); usuarioSelect.focus(); return false; }
        if (!recurso) { e.preventDefault(); alert('⚠️ Por favor, selecciona un Recurso'); recursoSelect.focus(); return false; }
        if (!fechaPrestamo) { e.preventDefault(); alert('⚠️ Por favor, completa la Fecha de Préstamo'); fechaPrestamoInput.focus(); return false; }
        if (!estado) { e.preventDefault(); alert('⚠️ Por favor, selecciona un Estado'); estadoSelect.focus(); return false; }
        
        if (fechaDevolucion && estado !== 'devuelto') {
            e.preventDefault();
            alert('⚠️ Si hay una fecha de devolución, el estado debe ser "Devuelto"');
            estadoSelect.focus();
            return false;
        }
    });

    validarFormulario();
});
</script>
@endsection