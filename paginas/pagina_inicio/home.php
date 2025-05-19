<?php
// home.php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Maky System</title>
    <link rel="icon" href="../../static/img/fondo_aguilas_saber.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-3" id="sidebar">
            <div class="text-center mb-4">
                <img src="../../static/img/fondo_aguilas_saber.png" alt="Logo Maky System" class="img-fluid mb-2" style="max-height: 80px;">
                <h4>Maky System</h4>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="../../paginas/pagina_inicio/dashboard.html" class="nav-link text-white">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="home.php" class="nav-link active text-white">
                        <i class="fas fa-home me-2"></i>Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#adminSubmenu" class="nav-link text-white" data-bs-toggle="collapse" aria-expanded="false" aria-controls="adminSubmenu">
                        <i class="fas fa-cogs me-2"></i>Administración <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="adminSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a href="../../paginas/usuarios/listado-usuarios.php" class="nav-link text-white">
                                    <i class="fas fa-users me-2"></i>Usuarios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../../paginas/recursos/recursos.php" class="nav-link text-white">
                                    <i class="fas fa-boxes me-2"></i>Recursos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../../paginas/prestamos/prestamos.php" class="nav-link text-white">
                                    <i class="fas fa-archive me-2"></i>Préstamos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../../paginas/devoluciones/devoluciones.php" class="nav-link text-white">
                                    <i class="fas fa-undo me-2"></i>Devoluciones
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../../paginas/productos/listado-productos.php" class="nav-link text-white">
                                    <i class="fas fa-shopping-cart me-2"></i>Productos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../../paginas/roles/listado-roles.php" class="nav-link text-white">
                                    <i class="fas fa-user-shield me-2"></i>Rol
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../../paginas/roles/gestion-permisos/gestion-permisos.php" class="nav-link text-white">
                                    <i class="fas fa-key me-2"></i>Permisos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../../paginas/recursos/estado-recursos/estado-recursos.php" class="nav-link text-white">
                                    <i class="fas fa-chart-line me-2"></i>Estado de Recursos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../../paginas/productos/ingreso-productos/ingreso-productos.php" class="nav-link text-white">
                                    <i class="fas fa-arrow-up me-2"></i>Ingresos
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <hr class="text-secondary">
            <a href="../../index.php" class="btn btn-danger w-100">Cerrar sesión</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <h1 class="main-header mb-3">Bienvenido a Maky System</h1>
            <p class="text-secondary mb-4">Una plataforma integral para gestionar usuarios, préstamos, productos y más de forma eficiente.</p>
            <div class="row">
                <?php
                $cards = [
                    ["icon" => "fas fa-users", "color" => "primary", "title" => "Gestión de Usuarios", "text" => "Administra información de usuarios registrados en el sistema.", "href" => "../../paginas/usuarios/listado-usuarios.php", "btn" => "primary", "label" => "Ir a Usuarios"],
                    ["icon" => "fas fa-archive", "color" => "success", "title" => "Gestión de Préstamos", "text" => "Supervisa y gestiona los préstamos de artículos o libros.", "href" => "../../paginas/prestamos/prestamos.php", "btn" => "success", "label" => "Ir a Préstamos"],
                    ["icon" => "fas fa-undo", "color" => "warning", "title" => "Gestión de Devoluciones", "text" => "Controla las devoluciones y asegura el inventario.", "href" => "../../paginas/devoluciones/devoluciones.php", "btn" => "warning", "label" => "Ir a Devoluciones"],
                    ["icon" => "fas fa-shopping-cart", "color" => "danger", "title" => "Gestión de Productos", "text" => "Monitorea y organiza el inventario de productos disponibles.", "href" => "../../paginas/productos/listado-productos.php", "btn" => "danger", "label" => "Ir a Productos"],
                    ["icon" => "fas fa-database", "color" => "info", "title" => "Recursos", "text" => "Controla y organiza los recursos de manera eficiente.", "href" => "../../paginas/recursos/recursos.php", "btn" => "info", "label" => "Ir a Recursos"],
                    ["icon" => "fas fa-user-shield", "color" => "dark", "title" => "Gestión de Roles", "text" => "Administra y asigna roles y permisos a los usuarios del sistema.", "href" => "../roles/listado-roles.php", "btn" => "dark", "label" => "Ir a Roles"],
                    ["icon" => "fas fa-key", "color" => "secondary", "title" => "Gestión de Permisos", "text" => "Administra los permisos y roles asignados a los usuarios.", "href" => "../../paginas/roles/gestion-permisos/gestion-permisos.php", "btn" => "secondary", "label" => "Ir a Permisos"],
                    ["icon" => "fas fa-chart-line", "color" => "warning", "title" => "Ver Estado de Recursos", "text" => "Consulta el estado actual y la disponibilidad de los recursos.", "href" => "../../paginas/recursos/estado-recursos/estado-recursos.php", "btn" => "warning", "label" => "Ir a Estado de Recursos"],
                    ["icon" => "fas fa-arrow-up", "color" => "info", "title" => "Gestión de Ingresos", "text" => "Administra los ingresos de productos y recursos al sistema.", "href" => "../../paginas/productos/ingreso-productos/ingreso-productos.php", "btn" => "info", "label" => "Ir a Ingresos"]
                ];

                foreach ($cards as $card) {
                    echo '<div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="' . $card["icon"] . ' fa-3x text-' . $card["color"] . ' mb-3"></i>
                                <h5 class="card-title">' . $card["title"] . '</h5>
                                <p class="card-text">' . $card["text"] . '</p>
                                <a href="' . $card["href"] . '" class="btn btn-' . $card["btn"] . '">' . $card["label"] . '</a>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-danger text-white text-center py-3">
        © 2024 Maky System. Todos los derechos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('load', function () {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('visible');
                }, 200 * (index + 1));
            });
        });
    </script>
</body>
</html>