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
    <form action="{{ route('productos.update', $producto) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control" required>
                <option value="disponible" {{ $producto->estado == 'disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="agotado" {{ $producto->estado == 'agotado' ? 'selected' : '' }}>Agotado</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Fecha Entrada</label>
            <input type="date" name="fecha_entrada" class="form-control" value="{{ old('fecha_entrada', $producto->fecha_entrada) }}" required>
        </div>
        <div class="mb-3">
            <label>Fecha Salida</label>
            <input type="date" name="fecha_salida" class="form-control" value="{{ old('fecha_salida', $producto->fecha_salida) }}" required>
        </div>
        <div class="mb-3">
            <label>Cantidad</label>
            <input type="number" name="cantidad" class="form-control" value="{{ old('cantidad', $producto->cantidad) }}" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection