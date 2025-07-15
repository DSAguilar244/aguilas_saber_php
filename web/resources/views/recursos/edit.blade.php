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
                   placeholder="Ej: Proyector Epson, Laptop HP">
        </div>

        <div class="mb-3">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control"
                   value="{{ old('descripcion', $recurso->descripcion) }}"
                   placeholder="Opcional: especificaciones o detalles relevantes">
        </div>

        <div class="mb-3">
            <label for="cantidad">Cantidad <span class="text-danger">*</span></label>
            <input type="number" name="cantidad" id="cantidad" class="form-control"
                   value="{{ old('cantidad', $recurso->cantidad) }}" required min="1"
                   placeholder="Ej: 3">
        </div>

        <div class="mb-3">
            <label for="estado">Estado <span class="text-danger">*</span></label>
            <select name="estado" id="estado" class="form-control" required>
                <option value="Disponible" {{ old('estado', $recurso->estado) == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="Regular" {{ old('estado', $recurso->estado) == 'Regular' ? 'selected' : '' }}>Regular</option>
                <option value="Deteriorado" {{ old('estado', $recurso->estado) == 'Deteriorado' ? 'selected' : '' }}>Deteriorado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('recursos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nombreInput = document.getElementById('nombre');
    const actualizarBtn = document.querySelector('button[type="submit"]');
    const recursoOriginal = "{{ $recurso->nombre }}";
    let timer = null;

    // Crea el div de error si no existe
    let errorDiv = document.getElementById('nombre-error');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'nombre-error';
        errorDiv.className = 'text-danger small mt-1';
        nombreInput.parentNode.appendChild(errorDiv);
    }

    nombreInput.addEventListener('input', function () {
        const nombre = this.value.trim();
        clearTimeout(timer);

        errorDiv.style.display = 'none';
        actualizarBtn.disabled = false;

        if (!nombre || nombre.toLowerCase() === recursoOriginal.toLowerCase()) {
            return; // Permitir si está vacío o igual al original
        }

        timer = setTimeout(() => {
            fetch("{{ route('recursos.validarNombre') }}", {
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
                    errorDiv.innerText = 'Ya existe un recurso con ese nombre.';
                    errorDiv.style.display = 'block';
                    actualizarBtn.disabled = true;
                } else {
                    errorDiv.innerText = '';
                    errorDiv.style.display = 'none';
                    actualizarBtn.disabled = false;
                }
            });
        }, 400);
    });
});
</script>
@endsection