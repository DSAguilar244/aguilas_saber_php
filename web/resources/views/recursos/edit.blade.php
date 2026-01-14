@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Editar Recurso</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('recursos.update', $recurso) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre" id="nombre" class="form-control"
                   value="{{ old('nombre', $recurso->nombre) }}" required maxlength="100"
                   placeholder="Ej: Laptop HP, Proyector Epson">
        </div>

        <div class="mb-3">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control"
                   value="{{ old('descripcion', $recurso->descripcion) }}"
                   maxlength="255" placeholder="Opcional: detalles técnicos o estado físico">
        </div>

        <div class="mb-3">
            <label for="cantidad">Cantidad <span class="text-danger">*</span></label>
            <input type="number" name="cantidad" id="cantidad" class="form-control"
                   value="{{ old('cantidad', $recurso->cantidad) }}"
                   required min="0" placeholder="Ej: 3">
        </div>

        <div class="mb-3">
            <label for="estado">Estado <span class="text-danger">*</span></label>
            <select name="estado" id="estado" class="form-control" required>
                <option value="bueno" {{ old('estado', $recurso->estado) == 'bueno' ? 'selected' : '' }}>Bueno</option>
                <option value="regular" {{ old('estado', $recurso->estado) == 'regular' ? 'selected' : '' }}>Regular</option>
                <option value="deteriorado" {{ old('estado', $recurso->estado) == 'deteriorado' ? 'selected' : '' }}>Deteriorado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" id="actualizar-btn">Actualizar</button>
        <a href="{{ route('recursos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nombreInput = document.getElementById('nombre');
    const cantidadInput = document.getElementById('cantidad');
    const estadoInput = document.getElementById('estado');
    const actualizarBtn = document.getElementById('actualizar-btn');
    const form = document.querySelector('form');

    function validarFormulario() {
        const nombre = nombreInput.value.trim();
        const cantidad = cantidadInput.value;
        const estado = estadoInput.value;

        const todosCompletos = nombre && cantidad !== '' && cantidad >= 0 && estado;
        actualizarBtn.disabled = !todosCompletos;
    }

    [nombreInput, cantidadInput, estadoInput].forEach(input => {
        input.addEventListener('change', validarFormulario);
        input.addEventListener('input', validarFormulario);
    });

    form.addEventListener('submit', function (e) {
        const nombre = nombreInput.value.trim();
        const cantidad = cantidadInput.value;
        const estado = estadoInput.value;

        if (!nombre) { e.preventDefault(); alert('⚠️ Por favor, completa el campo Nombre'); nombreInput.focus(); return false; }
        if (cantidad === '' || cantidad < 0) { e.preventDefault(); alert('⚠️ La cantidad debe ser 0 o mayor'); cantidadInput.focus(); return false; }
        if (!estado) { e.preventDefault(); alert('⚠️ Por favor, selecciona un Estado'); estadoInput.focus(); return false; }
    });

    validarFormulario();
});
</script>
@endsection