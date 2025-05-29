<?php
session_start();

if (!isset($_SESSION["usuarios"])) {
    $_SESSION["usuarios"] = [];
}

// Configuración paginación
$porPagina = 5;
$totalUsuarios = count($_SESSION["usuarios"]);
$totalPaginas = $totalUsuarios > 0 ? ceil($totalUsuarios / $porPagina) : 1;

$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$paginaActual = max(1, min($paginaActual, $totalPaginas));

$indiceInicio = ($paginaActual - 1) * $porPagina;
$indiceFin = min($indiceInicio + $porPagina, $totalUsuarios);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Usuarios</title>
    <link rel="icon" href="../../static/img/fondo_aguilas_saber.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="usuario.css" rel="stylesheet">
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
                <div class="collapse show" id="adminSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item"><a href="../../paginas/usuarios/listado-usuarios.php" class="nav-link active text-white"><i class="fas fa-users me-2"></i>Usuarios</a></li>
                        <li class="nav-item"><a href="../../paginas/recursos/recursos.php" class="nav-link text-white"><i class="fas fa-boxes me-2"></i>Recursos</a></li>
                        <li class="nav-item"><a href="../../paginas/prestamos/prestamos.php" class="nav-link text-white"><i class="fas fa-archive me-2"></i>Préstamos</a></li>
                        <li class="nav-item"><a href="../../paginas/devoluciones/devoluciones.php" class="nav-link text-white"><i class="fas fa-undo me-2"></i>Devoluciones</a></li>
                        <li class="nav-item"><a href="../../paginas/productos/listado-productos.php" class="nav-link text-white"><i class="fas fa-shopping-cart me-2"></i>Productos</a></li>
                        <li class="nav-item"><a href="../../paginas/roles/listado-roles.php" class="nav-link text-white"><i class="fas fa-user-shield me-2"></i>Rol</a></li>
                        <li class="nav-item"><a href="../../paginas/roles/gestion-permisos/gestion-permisos.php" class="nav-link text-white"><i class="fas fa-key me-2"></i>Permisos</a></li>
                        <li class="nav-item"><a href="../../paginas/recursos/estado-recursos/estado-recursos.php" class="nav-link text-white"><i class="fas fa-chart-line me-2"></i>Estado de Recursos</a></li>
                        <li class="nav-item"><a href="../../paginas/productos/ingreso-productos/ingreso-productos.php" class="nav-link text-white"><i class="fas fa-arrow-up me-2"></i>Ingresos</a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <hr class="text-secondary">
        <a href="../../index.php" class="btn btn-danger w-100">Cerrar sesión</a>
    </div>

    <!-- Contenido principal -->
    <div class="flex-grow-1 p-4 bg-light d-flex flex-column">
        <div>
            <h1 class="h4 mb-2">Listado de Usuarios</h1>
            <hr style="margin: 0.5rem 0;">
            <p class="mt-1">Agregar, editar o eliminar usuarios del sistema</p>

            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#usuarioModal">Agregar Usuario</button>

            <input type="text" id="busquedaUsuario" class="form-control mb-3" placeholder="Buscar usuario por nombre...">

            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo Electrónico</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody id="usuarioTableBody">
                <?php if ($totalUsuarios > 0): 
                    for ($i = $indiceInicio; $i < $indiceFin; $i++): 
                        $u = $_SESSION["usuarios"][$i]; ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['apellido']) ?></td>
                        <td><?= htmlspecialchars($u['correo']) ?></td>
                        <td><?= htmlspecialchars($u['telefono']) ?></td>
                        <td><?= htmlspecialchars($u['rol']) ?></td>
                        <td><?= htmlspecialchars($u['estado']) ?></td>
                        <td>
                            <button class="btn btn-icon text-primary" data-bs-toggle="modal" data-bs-target="#editUsuarioModal"
                                data-id="<?= $i ?>"
                                data-nombre="<?= htmlspecialchars($u['nombre']) ?>"
                                data-apellido="<?= htmlspecialchars($u['apellido']) ?>"
                                data-correo="<?= htmlspecialchars($u['correo']) ?>"
                                data-telefono="<?= htmlspecialchars($u['telefono']) ?>"
                                data-rol="<?= htmlspecialchars($u['rol']) ?>"
                                data-estado="<?= htmlspecialchars($u['estado']) ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                        <td>
                            <form action="delete.php" method="POST" onsubmit="return confirm('¿Eliminar este usuario?');">
                                <input type="hidden" name="usuario_id" value="<?= $i ?>">
                                <button class="btn btn-icon text-danger"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endfor; else: ?>
                    <tr><td colspan="9" class="text-center">No hay usuarios registrados.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <nav aria-label="Paginación de usuarios">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $paginaActual - 1 ?>">Anterior</a>
                    </li>
                    <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
                        <li class="page-item <?= $p == $paginaActual ? 'active' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $p ?>"><?= $p ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $paginaActual + 1 ?>">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<footer class="bg-danger text-white text-center py-3 mt-auto">
    © 2024 Maky System. Todos los derechos reservados.
</footer>

<!-- Modal Agregar Usuario -->
<div class="modal fade" id="usuarioModal" tabindex="-1" aria-labelledby="usuarioModalLabel" aria-hidden="true">
    <form class="modal-dialog" method="POST" action="crear.php">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input class="form-control mb-2" name="nombre" placeholder="Nombre" required>
                <input class="form-control mb-2" name="apellido" placeholder="Apellido" required>
                <input class="form-control mb-2" name="correo" placeholder="Correo Electrónico" required>
                <input class="form-control mb-2" name="telefono" placeholder="Teléfono" required>
                <input class="form-control mb-2" name="rol" placeholder="Rol" required>
                <select class="form-control mb-2" name="estado" required>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="editUsuarioModal" tabindex="-1" aria-labelledby="editUsuarioModalLabel" aria-hidden="true">
    <form class="modal-dialog" method="POST" action="editar.php">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="usuario_id" id="editUsuarioId">
                <input class="form-control mb-2" name="nombre" id="editUsuarioNombre" required>
                <input class="form-control mb-2" name="apellido" id="editUsuarioApellido" required>
                <input class="form-control mb-2" name="correo" id="editUsuarioCorreo" required>
                <input class="form-control mb-2" name="telefono" id="editUsuarioTelefono" required>
                <input class="form-control mb-2" name="rol" id="editUsuarioRol" required>
                <select class="form-control mb-2" name="estado" id="editUsuarioEstado" required>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const editUsuarioModal = document.getElementById('editUsuarioModal');
    editUsuarioModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('editUsuarioId').value = button.getAttribute('data-id');
        document.getElementById('editUsuarioNombre').value = button.getAttribute('data-nombre');
        document.getElementById('editUsuarioApellido').value = button.getAttribute('data-apellido');
        document.getElementById('editUsuarioCorreo').value = button.getAttribute('data-correo');
        document.getElementById('editUsuarioTelefono').value = button.getAttribute('data-telefono');
        document.getElementById('editUsuarioRol').value = button.getAttribute('data-rol');
        document.getElementById('editUsuarioEstado').value = button.getAttribute('data-estado');
    });

    document.getElementById('busquedaUsuario').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        document.querySelectorAll('#usuarioTableBody tr').forEach(row => {
            const nombre = row.children[1].textContent.toLowerCase();
            row.style.display = nombre.includes(filtro) ? '' : 'none';
        });
    });
</script>
</body>
</html>