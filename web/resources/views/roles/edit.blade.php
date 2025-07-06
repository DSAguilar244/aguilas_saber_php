@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/recurso.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Editar Rol</h2>
    <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $role->nombre) }}" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" value="{{ old('descripcion', $role->descripcion) }}">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection