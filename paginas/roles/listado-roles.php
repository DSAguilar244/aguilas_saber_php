<?php
session_start();

class Rol {
    public function __construct(public string $nombre, public string $descripcion, public string $estado) {}
}

if (!isset($_SESSION["roles"])) {
    $_SESSION["roles"] = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    <link rel="icon" href="../../static/img/fondo_aguilas_saber.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="roles.css" rel="stylesheet">
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
                    <a href="../../paginas/pagina_inicio/home.php" class="nav-link text-white">
                        <i class="fas fa-home me-2"></i>Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#adminSubmenu" class="nav-link text-white" data-bs-toggle="collapse" aria-expanded="false" aria-controls="adminSubmenu">
                        <i class="fas fa-cogs me-2"></i>Administración <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="adminSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a href="../../paginas/usuarios/listado-usuarios.php" class="nav-link text-white"><i class="fas fa-users me-2"></i>Usuarios</a></li>
                        <li class="nav-item"><a href="../../paginas/recursos/recursos.php" class="nav-link text-white"><i class="fas fa-boxes me-2"></i>Recursos</a></li>
                        <li class="nav-item"><a href="../../paginas/prestamos/prestamos.php" class="nav-link text-white"><i class="fas fa-archive me-2"></i>Préstamos</a></li>
                        <li class="nav-item"><a href="../../paginas/devoluciones/devoluciones.php" class="nav-link text-white"><i class="fas fa-undo me-2"></i>Devoluciones</a></li>
                        <li class="nav-item"><a href="../../paginas/productos/listado-productos.php" class="nav-link text-white"><i class="fas fa-shopping-cart me-2"></i>Productos</a></li>
                        <li class="nav-item"><a href="../../paginas/roles/listado-roles.php" class="nav-link active text-white"><i class="fas fa-user-shield me-2"></i>Rol</a></li>
                        <li class="nav-item"><a href="../../paginas/roles/gestion-permisos/gestion-permisos.php" class="nav-link text-white"><i class="fas fa-key me-2"></i>Permisos</a></li>
                        <li class="nav-item"><a href="../../paginas/recursos/estado-recursos/estado-recursos.php" class="nav-link text-white"><i class="fas fa-chart-line me-2"></i>Estado de Recursos</a></li>
                        <li class="nav-item"><a href="../../paginas/productos/ingreso-productos/ingreso-productos.php" class="nav-link text-white"><i class="fas fa-arrow-up me-2"></i>Ingresos</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
            <hr class="text-secondary">
            <a href="../../index.html" class="btn btn-danger w-100">Cerrar sesión</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4 bg-light">
            <h1 class="h4 mb-2">Gestión de Roles</h1>
            <hr style="margin: 0.5rem 0; border: 1px solid rgba(0, 0, 0, 0.1); box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
            <p class="mt-1">Llenar los campos para registrar o editar roles</p>
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#roleModal">Agregar Rol</button>

            <!-- Modal Agregar Rol -->
            <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
                <form class="modal-dialog" method="POST" action="/paginas/roles/crear.php">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="roleModalLabel">Agregar Rol</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="roleName" class="form-label">Seleccionar Rol</label>
                                <input class="form-control" name="nombre_rol" id="roleName" required />
                            </div>
                            <div class="mb-3">
                                <label for="roleDescription" class="form-label">Descripción</label>
                                <textarea class="form-control" name="descripcion_rol" id="roleDescription" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="roleStatus" class="form-label">Estado</label>
                                <select class="form-control" name="estado_rol" id="roleStatus" required>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" id="saveRoleBtn">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla de Roles -->
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre del Rol</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody id="roleTableBody">
                    <?php foreach ($_SESSION["roles"] as $index => $rol): ?>
                        <tr>
                            <td><?= $index ?></td>
                            <td><?= htmlspecialchars($rol->nombre) ?></td>
                            <td><?= htmlspecialchars($rol->descripcion) ?></td>
                            <td><?= $rol->estado ?></td>
                            <td>
                                <button type="button"
                                        class="btn btn-icon text-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editRoleModal"
                                        data-id="<?= $index ?>"
                                        data-nombre="<?= htmlspecialchars($rol->nombre) ?>"
                                        data-descripcion="<?= htmlspecialchars($rol->descripcion) ?>"
                                        data-estado="<?= $rol->estado ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                            <td>
                                <form action="delete.php" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este recurso?');">
                                    <input type="hidden" name="rol_id" value="<?= $index ?>">
                                    <button class="btn btn-icon text-danger"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Editar Rol -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <form class="modal-dialog" method="POST" action="/paginas/roles/editar.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="rol_id" id="editRoleId">
                    <div class="mb-3">
                        <label for="editRoleName" class="form-label">Nombre</label>
                        <input class="form-control" name="nombre_rol" id="editRoleName" required />
                    </div>
                    <div class="mb-3">
                        <label for="editRoleDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion_rol" id="editRoleDescription" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editRoleStatus" class="form-label">Estado</label>
                        <select class="form-control" name="estado_rol" id="editRoleStatus" required>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-danger text-white text-center py-3">
        © 2024 Maky System. Todos los derechos reservados.
    </footer>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const editModal = document.getElementById('editRoleModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('editRoleId').value = button.getAttribute('data-id');
            document.getElementById('editRoleName').value = button.getAttribute('data-nombre');
            document.getElementById('editRoleDescription').value = button.getAttribute('data-descripcion');
            document.getElementById('editRoleStatus').value = button.getAttribute('data-estado');
        });
    </script>
</body>
</html>