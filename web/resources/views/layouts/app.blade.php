<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aguilas del Saber')</title>
    <link rel="icon" href="{{ asset('static/img/fondo_aguilas_saber.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @yield('styles')
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-3" id="sidebar">
            <div class="text-center mb-4">
                <img src="{{ asset('static/img/fondo_aguilas_saber.png') }}" alt="Logo Aguilas del Saber"
                    class="img-fluid mb-2" style="max-height: 80px;">
                <h4>Aguilas del Saber</h4>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('home') }}"
                        class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="fas fa-home me-2"></i>Inicio
                    </a>
                </li>

                <!-- Administración -->
                <li class="nav-item">
                    <a href="#adminSubmenu" class="nav-link text-white" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="adminSubmenu">
                        <i class="fas fa-cogs me-2"></i>Administración <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="adminSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a href="{{ route('usuarios.index') }}"
                                    class="nav-link text-white {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                                    <i class="fas fa-users me-2"></i>Usuarios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('recursos.index') }}"
                                    class="nav-link text-white {{ request()->routeIs('recursos.*') ? 'active' : '' }}">
                                    <i class="fas fa-boxes me-2"></i>Recursos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('prestamos.index') }}"
                                    class="nav-link text-white {{ request()->routeIs('prestamos.*') ? 'active' : '' }}">
                                    <i class="fas fa-archive me-2"></i>Préstamos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('productos.index') }}"
                                    class="nav-link text-white {{ request()->routeIs('productos.*') ? 'active' : '' }}">
                                    <i class="fas fa-shopping-cart me-2"></i>Productos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}"
                                    class="nav-link text-white {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-shield me-2"></i>Roles
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Reportes -->
                <li class="nav-item">
                    <a href="#reportesSubmenu" class="nav-link text-white" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="reportesSubmenu">
                        <i class="fas fa-file-alt me-2"></i>Reportes <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="reportesSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a href="{{ url('/reporte-productos') }}" target="_blank" class="nav-link text-white">
                                    <i class="far fa-circle me-2"></i>Productos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/reporte-prestamos') }}" target="_blank" class="nav-link text-white">
                                    <i class="far fa-circle me-2"></i>Préstamos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/reporte-usuarios') }}" target="_blank" class="nav-link text-white">
                                    <i class="far fa-circle me-2"></i>Usuarios
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>

            <hr class="text-secondary">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Cerrar sesión</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>
    </div>

    <footer class="bg-danger text-white text-center py-3">
        © 2024 Aguilas del Saber. Todos los derechos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>