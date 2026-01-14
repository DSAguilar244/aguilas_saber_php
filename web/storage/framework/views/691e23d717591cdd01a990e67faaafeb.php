<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- 🔎 SEO: Título y descripción -->
    <title><?php echo $__env->yieldContent('title', 'Inventario Escolar | Las Águilas del Saber - El Oro, Ecuador'); ?></title>
    <meta name="description" content="Sistema de inventario para la escuela Las Águilas del Saber en El Oro, Ecuador. Administra recursos, préstamos y usuarios escolares fácilmente.">

    <!-- 🧠 SEO: Palabras clave -->
    <meta name="keywords" content="Las Águilas del Saber, inventario escolar, gestión de recursos, El Oro Ecuador, colegio, escuela, préstamo de materiales, panel administrativo, control educativo, educación básica, biblioteca escolar">

    <!-- 📍 SEO: Localización y autor -->
    <meta name="author" content="Escuela Las Águilas del Saber">
    <meta name="geo.region" content="EC-O">
    <meta name="geo.placename" content="Machala, El Oro, Ecuador">
    <meta name="language" content="Spanish">

    <!-- 📷 Favicon -->
    <link rel="icon" href="<?php echo e(asset('static/img/fondo_aguilas_saber.png')); ?>" type="image/x-icon">

    <!-- 💬 SEO: Open Graph para redes sociales -->
    <meta property="og:title" content="Sistema de Inventario - Las Águilas del Saber">
    <meta property="og:description" content="Plataforma escolar para la gestión de préstamos y recursos educativos en Las Águilas del Saber. Ubicada en El Oro, Ecuador.">
    <meta property="og:image" content="<?php echo e(asset('static/img/fondo_aguilas_saber.png')); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:type" content="website">

    <!-- 🔗 Estilos y librerías -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/styles.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/usuario.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/pagination-fix.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldContent('styles'); ?>
</head>

<body>
    <!-- 🔼 Barra hamburguesa solo en móviles -->
    <nav class="navbar navbar-dark bg-dark d-md-none">
        <div class="container-fluid justify-content-between">
            <span class="navbar-brand">Las Águilas del Saber</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- 📱 Menú lateral móvil -->
    <div class="offcanvas offcanvas-start text-bg-dark d-md-none" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menú</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <div class="layout-wrapper">
        <!-- 🧭 Sidebar fijo escritorio -->
        <nav id="sidebar" class="d-none d-md-flex flex-column">
            <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </nav>

        <!-- 📄 Contenido principal -->
        <main id="main-content">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <footer class="footer">
        © 2024 Las Águilas del Saber. Todos los derechos reservados.
    </footer>

    <!-- 🚀 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH E:\Dev-proyectos\aguilas_mobile_react-main\aguilas_saber_php\web\resources\views/layouts/app.blade.php ENDPATH**/ ?>