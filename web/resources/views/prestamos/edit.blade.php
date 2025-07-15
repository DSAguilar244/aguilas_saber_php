@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <h2>Editar Préstamo</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('prestamos.update', $prestamo) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Código</label>
            <input type="text" name="codigo" class="form-control" value="{{ old('codigo', $prestamo->codigo) }}" required>
        </div>
        <div class="mb-3">
            <label>Usuario</label>
            <select name="usuario_id" class="form-control" required>
                <option value="">Seleccione un usuario</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" {{ (old('usuario_id', $prestamo->usuario_id) == $usuario->id) ? 'selected' : '' }}>
                        {{ $usuario->nombre }} {{ $usuario->apellido }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Recurso</label>
            <select name="recurso_id" class="form-control" required>
                <option value="">Seleccione un recurso</option>
                @foreach($recursos as $recurso)
                    <option value="{{ $recurso->id }}" {{ (old('recurso_id', $prestamo->recurso_id) == $recurso->id) ? 'selected' : '' }}>
                        {{ $recurso->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Fecha Préstamo</label>
            <input type="date" name="fecha_prestamo" class="form-control" value="{{ old('fecha_prestamo', $prestamo->fecha_prestamo) }}" required>
        </div>
        <div class="mb-3">
            <label>Fecha Devolución</label>
            <input type="date" name="fecha_devolucion" class="form-control" value="{{ old('fecha_devolucion', $prestamo->fecha_devolucion) }}">
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control" required>
                <option value="pendiente" {{ (old('estado', $prestamo->estado) == 'pendiente') ? 'selected' : '' }}>Pendiente</option>
                <option value="devuelto" {{ (old('estado', $prestamo->estado) == 'devuelto') ? 'selected' : '' }}>Devuelto</option>
                <option value="atrasado" {{ (old('estado', $prestamo->estado) == 'atrasado') ? 'selected' : '' }}>Atrasado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection