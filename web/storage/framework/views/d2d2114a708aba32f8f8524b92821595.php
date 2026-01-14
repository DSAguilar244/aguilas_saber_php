<div class="sidebar-header text-center mb-4">
    <img src="<?php echo e(asset('static/img/fondo_aguilas_saber.png')); ?>" alt="Logo" class="img-fluid mb-2" style="max-height: 80px;">
    <h4>Las Águilas del Saber</h4>
</div>

<ul class="nav flex-column">
    <!-- Dashboard -->
    <li>
        <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </a>
    </li>

    <!-- Administración -->
    <?php
        $isAdmin = auth()->check() && auth()->user()->roles->contains('name', 'admin');
        $adminOpen = request()->routeIs('usuarios.*') || request()->routeIs('recursos.*') ||
                     request()->routeIs('prestamos.*') || request()->routeIs('productos.*') ||
                     ($isAdmin && request()->routeIs('roles.*')) || request()->routeIs('auditorias.*');
    ?>
    <li>
        <a class="nav-link" data-bs-toggle="collapse" href="#adminSubmenu" role="button"
           aria-expanded="<?php echo e($adminOpen ? 'true' : 'false'); ?>"
           aria-controls="adminSubmenu">
            <i class="fas fa-cogs me-2"></i>Administración
            <i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <ul class="collapse <?php echo e($adminOpen ? 'show' : ''); ?>" id="adminSubmenu">
            <li>
                <a href="<?php echo e(route('usuarios.index')); ?>" class="nav-link <?php echo e(request()->routeIs('usuarios.*') ? 'active' : ''); ?>">
                    <i class="fas fa-users me-2"></i>Usuarios
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('recursos.index')); ?>" class="nav-link <?php echo e(request()->routeIs('recursos.*') ? 'active' : ''); ?>">
                    <i class="fas fa-database me-2"></i>Recursos
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('prestamos.index')); ?>" class="nav-link <?php echo e(request()->routeIs('prestamos.*') ? 'active' : ''); ?>">
                    <i class="fas fa-archive me-2"></i>Préstamos
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('productos.index')); ?>" class="nav-link <?php echo e(request()->routeIs('productos.*') ? 'active' : ''); ?>">
                    <i class="fas fa-shopping-cart me-2"></i>Productos
                </a>
            </li>
            <?php if($isAdmin): ?>
            <li>
                <a href="<?php echo e(route('roles.index')); ?>" class="nav-link <?php echo e(request()->routeIs('roles.*') ? 'active' : ''); ?>">
                    <i class="fas fa-user-shield me-2"></i>Roles
                </a>
            </li>
            <?php endif; ?>
            <?php if(auth()->user()->roles->contains('name', 'admin')): ?>
            <li>
                <a href="<?php echo e(route('auditorias.index')); ?>" class="nav-link <?php echo e(request()->routeIs('auditorias.*') ? 'active' : ''); ?>">
                    <i class="fas fa-history me-2"></i>Auditoría
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </li>

    <!-- Reportes -->
    <?php
        $reportesOpen = request()->is('reporte-*') || request()->routeIs('reportes.*');
    ?>
    <li>
        <a class="nav-link" data-bs-toggle="collapse" href="#reportesSubmenu" role="button"
           aria-expanded="<?php echo e($reportesOpen ? 'true' : 'false'); ?>"
           aria-controls="reportesSubmenu">
            <i class="fas fa-file-alt me-2"></i>Reportes
            <i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <ul class="collapse <?php echo e($reportesOpen ? 'show' : ''); ?>" id="reportesSubmenu">
            <li>
                <a href="<?php echo e(url('/reporte-productos')); ?>" target="_blank" class="nav-link">
                    <i class="far fa-circle me-2"></i>Productos
                </a>
            </li>
            <li>
                <a href="<?php echo e(url('/reporte-prestamos')); ?>" target="_blank" class="nav-link">
                    <i class="far fa-circle me-2"></i>Préstamos
                </a>
            </li>
            <li>
                <a href="<?php echo e(url('/reporte-usuarios')); ?>" target="_blank" class="nav-link">
                    <i class="far fa-circle me-2"></i>Usuarios
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('reportes.recursos.pdf')); ?>" target="_blank" class="nav-link">
                    <i class="far fa-circle me-2"></i>Recursos
                </a>
            </li>
        </ul>
    </li>
</ul>


<?php if(auth()->guard()->check()): ?>
<div class="card mb-3 bg-gradient border-0 shadow-sm mt-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px;">
    <div class="card-body p-3">
        <div class="text-center text-white">
            <i class="fas fa-user-circle fa-3x mb-2" style="color: #ffffff;"></i>
            <h6 class="mb-1 fw-bold text-white">¡Bienvenido!</h6>
            <p class="mb-1 fw-semibold text-white"><?php echo e(auth()->user()->nombre); ?> <?php echo e(auth()->user()->apellido); ?></p>
            <small class="text-white" style="opacity: 0.9;"><?php echo e(auth()->user()->email); ?></small>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Logout -->
<form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-3">
    <?php echo csrf_field(); ?>
    <button type="submit" class="btn btn-danger w-100">Cerrar sesión</button>
</form><?php /**PATH E:\Dev-proyectos\aguilas_mobile_react-main\aguilas_saber_php\web\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>