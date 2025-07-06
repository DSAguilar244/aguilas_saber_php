@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/recurso.css') }}" rel="stylesheet">
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
    <form action="{{ route('recursos.update', $recurso) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $recurso->nombre) }}" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" value="{{ old('descripcion', $recurso->descripcion) }}">
        </div>
        <div class="mb-3">
            <label>Cantidad</label>
            <input type="number" name="cantidad" class="form-control" value="{{ old('cantidad', $recurso->cantidad) }}" min="0" required>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control" required>
                <option value="bueno" {{ $recurso->estado == 'bueno' ? 'selected' : '' }}>Bueno</option>
                <option value="regular" {{ $recurso->estado == 'regular' ? 'selected' : '' }}>Regular</option>
                <option value="deteriorado" {{ $recurso->estado == 'deteriorado' ? 'selected' : '' }}>Deteriorado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('recursos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection