@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Editar Rol</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('roles.update', $role) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre" id="nombre" class="form-control"
                   value="{{ old('nombre', $role->nombre) }}" required maxlength="100"
                   placeholder="Ej: Administrador, Editor, Invitado">
        </div>

        <div class="mb-3">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control"
                   value="{{ old('descripcion', $role->descripcion) }}"
                   placeholder="Ej: Permite gestionar usuarios y configuraciones">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection