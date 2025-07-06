@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/recurso.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Agregar Recurso</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('recursos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" value="{{ old('descripcion') }}">
        </div>
        <div class="mb-3">
            <label>Cantidad</label>
            <input type="number" name="cantidad" class="form-control" value="{{ old('cantidad', 0) }}" min="0" required>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control" required>
                <option value="bueno" selected>Bueno</option>
                <option value="Regular">Regular</option>
                <option value="deteriorado">Deteriorado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('recursos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection