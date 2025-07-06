@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/recurso.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Agregar Rol</h2>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" value="{{ old('descripcion') }}">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection