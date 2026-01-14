<div class="sidebar-header text-center mb-4">
    <img src="{{ asset('static/img/fondo_aguilas_saber.png') }}" alt="Logo" class="img-fluid mb-2" style="max-height: 80px;">
    <h4>Las Águilas del Saber</h4>
</div>

<ul class="nav flex-column">
    <!-- Dashboard -->
    <li>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </a>
    </li>

    <!-- Administración -->
    @php
        $isAdmin = auth()->check() && auth()->user()->roles->contains('name', 'admin');
        $adminOpen = request()->routeIs('usuarios.*') || request()->routeIs('recursos.*') ||
                     request()->routeIs('prestamos.*') || request()->routeIs('productos.*') ||
                     ($isAdmin && request()->routeIs('roles.*')) || request()->routeIs('auditorias.*');
    @endphp
    <li>
        <a class="nav-link" data-bs-toggle="collapse" href="#adminSubmenu" role="button"
           aria-expanded="{{ $adminOpen ? 'true' : 'false' }}"
           aria-controls="adminSubmenu">
            <i class="fas fa-cogs me-2"></i>Administración
            <i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <ul class="collapse {{ $adminOpen ? 'show' : '' }}" id="adminSubmenu">
            <li>
                <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>Usuarios
                </a>
            </li>
            <li>
                <a href="{{ route('recursos.index') }}" class="nav-link {{ request()->routeIs('recursos.*') ? 'active' : '' }}">
                    <i class="fas fa-database me-2"></i>Recursos
                </a>
            </li>
            <li>
                <a href="{{ route('prestamos.index') }}" class="nav-link {{ request()->routeIs('prestamos.*') ? 'active' : '' }}">
                    <i class="fas fa-archive me-2"></i>Préstamos
                </a>
            </li>
            <li>
                <a href="{{ route('productos.index') }}" class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart me-2"></i>Productos
                </a>
            </li>
            @if($isAdmin)
            <li>
                <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield me-2"></i>Roles
                </a>
            </li>
            @endif
            @if(auth()->user()->roles->contains('name', 'admin'))
            <li>
                <a href="{{ route('auditorias.index') }}" class="nav-link {{ request()->routeIs('auditorias.*') ? 'active' : '' }}">
                    <i class="fas fa-history me-2"></i>Auditoría
                </a>
            </li>
            @endif
        </ul>
    </li>

    <!-- Reportes -->
    @php
        $reportesOpen = request()->is('reporte-*') || request()->routeIs('reportes.*');
    @endphp
    <li>
        <a class="nav-link" data-bs-toggle="collapse" href="#reportesSubmenu" role="button"
           aria-expanded="{{ $reportesOpen ? 'true' : 'false' }}"
           aria-controls="reportesSubmenu">
            <i class="fas fa-file-alt me-2"></i>Reportes
            <i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <ul class="collapse {{ $reportesOpen ? 'show' : '' }}" id="reportesSubmenu">
            <li>
                <a href="{{ url('/reporte-productos') }}" target="_blank" class="nav-link">
                    <i class="far fa-circle me-2"></i>Productos
                </a>
            </li>
            <li>
                <a href="{{ url('/reporte-prestamos') }}" target="_blank" class="nav-link">
                    <i class="far fa-circle me-2"></i>Préstamos
                </a>
            </li>
            <li>
                <a href="{{ url('/reporte-usuarios') }}" target="_blank" class="nav-link">
                    <i class="far fa-circle me-2"></i>Usuarios
                </a>
            </li>
            <li>
                <a href="{{ route('reportes.recursos.pdf') }}" target="_blank" class="nav-link">
                    <i class="far fa-circle me-2"></i>Recursos
                </a>
            </li>
        </ul>
    </li>
</ul>

{{-- 👤 Tarjeta de Usuario --}}
@auth
<div class="card mb-3 bg-gradient border-0 shadow-sm mt-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px;">
    <div class="card-body p-3">
        <div class="text-center text-white">
            <i class="fas fa-user-circle fa-3x mb-2" style="color: #ffffff;"></i>
            <h6 class="mb-1 fw-bold text-white">¡Bienvenido!</h6>
            <p class="mb-1 fw-semibold text-white">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</p>
            <small class="text-white" style="opacity: 0.9;">{{ auth()->user()->email }}</small>
        </div>
    </div>
</div>
@endauth

<!-- Logout -->
<form method="POST" action="{{ route('logout') }}" class="mt-3">
    @csrf
    <button type="submit" class="btn btn-danger w-100">Cerrar sesión</button>
</form>