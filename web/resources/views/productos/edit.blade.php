@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Editar Producto</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('productos.update', $producto) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre" id="nombre" class="form-control"
                   value="{{ old('nombre', $producto->nombre) }}" required maxlength="100"
                   placeholder="Ej: Monitor LED 24 pulgadas">
            <small class="form-text text-muted">El nombre debe ser único.</small>
            <div id="nombre-error" class="text-danger small mt-1" style="display: none;"></div>
        </div>

        <div class="mb-3">
            <label for="estado">Estado <span class="text-danger">*</span></label>
            <select name="estado" id="estado" class="form-control" required>
                <option value="disponible" {{ $producto->estado == 'disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="agotado" {{ $producto->estado == 'agotado' ? 'selected' : '' }}>Agotado</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha_entrada">Fecha Entrada <span class="text-danger">*</span></label>
            <input type="date" name="fecha_entrada" id="fecha_entrada" class="form-control"
                   value="{{ old('fecha_entrada', $producto->fecha_entrada) }}" required>
        </div>

        <div class="mb-3">
            <label for="fecha_salida">Fecha Salida <span class="text-danger">*</span></label>
            <input type="date" name="fecha_salida" id="fecha_salida" class="form-control"
                   value="{{ old('fecha_salida', $producto->fecha_salida) }}" required>
        </div>

        <div class="mb-3">
            <label for="cantidad">Cantidad <span class="text-danger">*</span></label>
            <input type="number" name="cantidad" id="cantidad" class="form-control"
                   value="{{ old('cantidad', $producto->cantidad) }}" min="0" required placeholder="Ej: 5">
        </div>

        <button type="submit" class="btn btn-primary" id="actualizar-btn">Actualizar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nombreInput = document.getElementById('nombre');
    const estadoInput = document.getElementById('estado');
    const fechaEntradaInput = document.getElementById('fecha_entrada');
    const fechaSalidaInput = document.getElementById('fecha_salida');
    const cantidadInput = document.getElementById('cantidad');
    const errorDiv = document.getElementById('nombre-error');
    const actualizarBtn = document.getElementById('actualizar-btn');
    const form = document.querySelector('form');
    const nombreOriginal = "{{ $producto->nombre }}".toLowerCase();
    let timer = null;

    function validarFormulario() {
        const nombre = nombreInput.value.trim();
        const estado = estadoInput.value;
        const fechaEntrada = fechaEntradaInput.value;
        const fechaSalida = fechaSalidaInput.value;
        const cantidad = cantidadInput.value;

        const todosCompletos = nombre && estado && fechaEntrada && fechaSalida && cantidad !== '' && cantidad >= 0;
        actualizarBtn.disabled = !todosCompletos;
    }

    nombreInput.addEventListener('input', function () {
        const nombre = nombreInput.value.trim().toLowerCase();
        clearTimeout(timer);

        validarFormulario();

        if (!nombre || nombre === nombreOriginal) {
            errorDiv.style.display = 'none';
            validarFormulario();
            return;
        }

        timer = setTimeout(() => {
            fetch("{{ route('productos.validarNombre') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ nombre })
            })
            .then(res => res.json())
            .then(data => {
                if (data.existe) {
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>⚠️ Este producto ya existe';
                    errorDiv.style.display = 'block';
                    actualizarBtn.disabled = true;
                } else {
                    errorDiv.style.display = 'none';
                    validarFormulario();
                }
            });
        }, 500);
    });

    [estadoInput, fechaEntradaInput, fechaSalidaInput, cantidadInput].forEach(input => {
        input.addEventListener('change', validarFormulario);
        input.addEventListener('input', validarFormulario);
    });

    form.addEventListener('submit', function (e) {
        const nombre = nombreInput.value.trim();
        const estado = estadoInput.value;
        const fechaEntrada = fechaEntradaInput.value;
        const fechaSalida = fechaSalidaInput.value;
        const cantidad = cantidadInput.value;

        if (!nombre) { e.preventDefault(); alert('⚠️ Por favor, completa el campo Nombre'); nombreInput.focus(); return false; }
        if (!estado) { e.preventDefault(); alert('⚠️ Por favor, selecciona un Estado'); estadoInput.focus(); return false; }
        if (!fechaEntrada) { e.preventDefault(); alert('⚠️ Por favor, completa la Fecha de Entrada'); fechaEntradaInput.focus(); return false; }
        if (!fechaSalida) { e.preventDefault(); alert('⚠️ Por favor, completa la Fecha de Salida'); fechaSalidaInput.focus(); return false; }
        if (cantidad === '' || cantidad < 0) { e.preventDefault(); alert('⚠️ La cantidad debe ser 0 o mayor'); cantidadInput.focus(); return false; }

        const entrada = new Date(fechaEntrada);
        const salida = new Date(fechaSalida);
        if (salida < entrada) {
            e.preventDefault();
            alert('⚠️ La fecha de salida debe ser igual o posterior a la fecha de entrada');
            fechaSalidaInput.focus();
            return false;
        }
    });

    validarFormulario();
});
</script>
@endsection