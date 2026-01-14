@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Editar Rol: {{ $role->name }}</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('roles.update', $role) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name', $role->name) }}" {{ $role->name === 'admin' ? 'readonly' : '' }}
                   required maxlength="100"
                   placeholder="Ej: Administrador, Editor, Invitado">
            @if($role->name === 'admin')
                <small class="text-muted">El rol de administrador no puede ser modificado</small>
            @endif
        </div>

        <div class="mb-3">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control"
                   value="{{ old('descripcion', $role->descripcion) }}"
                   placeholder="Ej: Permite gestionar usuarios y configuraciones">
        </div>

        @if($role->name !== 'admin')
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Permisos del Rol</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Selecciona los permisos que deseas asignar a este rol:</p>
                
                @php
                    $rolePermissions = $role->permissions->pluck('name')->toArray();
                    $gestiones = [
                        'usuarios' => ['icon' => 'fa-users', 'label' => 'Usuarios', 'color' => 'primary'],
                        'recursos' => ['icon' => 'fa-database', 'label' => 'Recursos', 'color' => 'info'],
                        'productos' => ['icon' => 'fa-shopping-cart', 'label' => 'Productos', 'color' => 'danger'],
                        'prestamos' => ['icon' => 'fa-archive', 'label' => 'Préstamos', 'color' => 'success'],
                    ];
                    $acciones = [
                        'crear' => ['icon' => 'fa-plus', 'label' => 'Crear'],
                        'editar' => ['icon' => 'fa-edit', 'label' => 'Editar'],
                        'eliminar' => ['icon' => 'fa-trash', 'label' => 'Eliminar'],
                    ];
                @endphp

                <div class="row">
                    @foreach($gestiones as $gestion => $info)
                    <div class="col-md-6 mb-4">
                        <div class="card border-{{ $info['color'] }}">
                            <div class="card-header bg-{{ $info['color'] }} text-white">
                                <i class="fas {{ $info['icon'] }} me-2"></i>{{ $info['label'] }}
                            </div>
                            <div class="card-body">
                                @foreach($acciones as $accion => $accionInfo)
                                    {{-- Omitir "Crear" y "Eliminar" para usuarios --}}
                                    @if($gestion === 'usuarios' && ($accion === 'crear' || $accion === 'eliminar'))
                                        @continue
                                    @endif
                                    
                                    @php
                                        $permissionName = "{$gestion}.{$accion}";
                                        $isChecked = in_array($permissionName, $rolePermissions);
                                    @endphp
                                    <div class="form-check mb-2">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               name="permissions[]" 
                                               value="{{ $permissionName }}" 
                                               id="permission_{{ $permissionName }}"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permission_{{ $permissionName }}">
                                            <i class="fas {{ $accionInfo['icon'] }} me-1"></i>{{ $accionInfo['label'] }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            El rol de <strong>Administrador</strong> tiene acceso total al sistema y no requiere configuración de permisos.
        </div>
        @endif

        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Actualizar</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i>Cancelar</a>
    </form>
</div>
@endsection