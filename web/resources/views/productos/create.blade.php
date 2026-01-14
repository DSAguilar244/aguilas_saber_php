@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Agregar Producto</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('productos.store') }}" method="POST" novalidate>
        @csrf

        <div class="mb-3">
            <label for="nombre">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre" id="nombre" class="form-control"
                   value="{{ old('nombre') }}" placeholder="Ej: Teclado inalámbrico" required maxlength="100">
            <small class="form-text text-muted">Debe ser único y descriptivo.</small>
            <div id="nombre-error" class="text-danger small mt-1" style="display: none;"></div>
        </div>

        <div class="mb-3">
            <label for="estado">Estado <span class="text-danger">*</span></label>
            <select name="estado" id="estado" class="form-control" required>
                <option value="disponible" {{ old('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="agotado" {{ old('estado') == 'agotado' ? 'selected' : '' }}>Agotado</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha_entrada">Fecha Entrada <span class="text-danger">*</span></label>
            <input type="date" name="fecha_entrada" id="fecha_entrada" class="form-control"
                   value="{{ old('fecha_entrada') }}" required>
        </div>

        <div class="mb-3">
            <label for="fecha_salida">Fecha Salida <span class="text-danger">*</span></label>
            <input type="date" name="fecha_salida" id="fecha_salida" class="form-control"
                   value="{{ old('fecha_salida') }}" required>
        </div>

        <div class="mb-3">
            <label for="cantidad">Cantidad <span class="text-danger">*</span></label>
            <input type="number" name="cantidad" id="cantidad" class="form-control"
                   value="{{ old('cantidad', 0) }}" min="0" required placeholder="Ej: 10">
        </div>

        <button type="submit" class="btn btn-success" id="guardar-btn">Guardar</button>
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
    const guardarBtn = document.getElementById('guardar-btn');
    const errorDiv = document.getElementById('nombre-error');
    const form = document.querySelector('form');
    let timer = null;

    // Función para validar todos los campos
    function validarFormulario() {
        const nombre = nombreInput.value.trim();
        const estado = estadoInput.value;
        const fechaEntrada = fechaEntradaInput.value;
        const fechaSalida = fechaSalidaInput.value;
        const cantidad = cantidadInput.value;

        const todosCamposCompletos = nombre && estado && fechaEntrada && fechaSalida && cantidad !== '' && cantidad >= 0;
        
        // Solo habilitar si todos los campos están completos y no hay error de nombre duplicado
        if (todosCamposCompletos && errorDiv.style.display === 'none') {
            guardarBtn.disabled = false;
        } else {
            guardarBtn.disabled = true;
        }
    }

    // Validar nombre en tiempo real
    nombreInput.addEventListener('input', function () {
        const nombre = nombreInput.value.trim();
        clearTimeout(timer);

        if (!nombre) {
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
                    guardarBtn.disabled = true;
                } else {
                    errorDiv.style.display = 'none';
                    validarFormulario();
                }
            });
        }, 500);
    });

    // Validar al cambiar otros campos
    [estadoInput, fechaEntradaInput, fechaSalidaInput, cantidadInput].forEach(input => {
        input.addEventListener('change', validarFormulario);
        input.addEventListener('input', validarFormulario);
    });

    // Validar antes de enviar
    form.addEventListener('submit', function (e) {
        const nombre = nombreInput.value.trim();
        const estado = estadoInput.value;
        const fechaEntrada = fechaEntradaInput.value;
        const fechaSalida = fechaSalidaInput.value;
        const cantidad = cantidadInput.value;

        if (!nombre) {
            e.preventDefault();
            alert('⚠️ Por favor, completa el campo Nombre');
            nombreInput.focus();
            return false;
        }
        if (!estado) {
            e.preventDefault();
            alert('⚠️ Por favor, selecciona un Estado');
            estadoInput.focus();
            return false;
        }
        if (!fechaEntrada) {
            e.preventDefault();
            alert('⚠️ Por favor, completa la Fecha de Entrada');
            fechaEntradaInput.focus();
            return false;
        }
        if (!fechaSalida) {
            e.preventDefault();
            alert('⚠️ Por favor, completa la Fecha de Salida');
            fechaSalidaInput.focus();
            return false;
        }
        if (!cantidad || cantidad < 0) {
            e.preventDefault();
            alert('⚠️ Por favor, completa la Cantidad (debe ser 0 o mayor)');
            cantidadInput.focus();
            return false;
        }

        // Validar que fecha salida sea >= fecha entrada
        const entrada = new Date(fechaEntradaInput.value);
        const salida = new Date(fechaSalidaInput.value);
        if (salida < entrada) {
            e.preventDefault();
            alert('⚠️ La fecha de salida debe ser igual o posterior a la fecha de entrada');
            fechaSalidaInput.focus();
            return false;
        }
    });

    // Validar al cargar (si hay datos viejos del formulario)
    validarFormulario();
});
</script>
@endsection