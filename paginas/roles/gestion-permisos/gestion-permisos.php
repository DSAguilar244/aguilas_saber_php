<?php
session_start();

if (!isset($_SESSION["permisos"])) {
    $_SESSION["permisos"] = [];
}

// Configuración paginación
$porPagina = 5;
$totalPermisos = count($_SESSION["permisos"]);
$totalPaginas = $totalPermisos > 0 ? ceil($totalPermisos / $porPagina) : 1;

$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaActual < 1) $paginaActual = 1;
if ($paginaActual > $totalPaginas) $paginaActual = $totalPaginas;

$indiceInicio = ($paginaActual - 1) * $porPagina;
$indiceFin = min($indiceInicio + $porPagina, $totalPermisos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Permisos</title>
    <link rel="icon" href="../../../static/img/fondo_aguilas_saber.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="permisos.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3" id="sidebar">
        <div class="text-center mb-4">
            <img src="../../../static/img/fondo_aguilas_saber.png" alt="Logo Maky System" class="img-fluid mb-2" style="max-height: 80px;">
            <h4>Maky System</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="../../../paginas/pagina_inicio/dashboard.html" class="nav-link text-white">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="../../../paginas/pagina_inicio/home.php" class="nav-link text-white">
                    <i class="fas fa-home me-2"></i>Inicio
                </a>
            </li>
            <li class="nav-item">
                <a href="#adminSubmenu" class="nav-link text-white" data-bs-toggle="collapse" aria-expanded="false" aria-controls="adminSubmenu">
                    <i class="fas fa-cogs me-2"></i>Administración <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse show" id="adminSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item"><a href="../../../paginas/usuarios/listado-usuarios.php" class="nav-link text-white"><i class="fas fa-users me-2"></i>Usuarios</a></li>
                        <li class="nav-item"><a href="../../../paginas/recursos/recursos.php" class="nav-link text-white"><i class="fas fa-boxes me-2"></i>Recursos</a></li>
                        <li class="nav-item"><a href="../../../paginas/prestamos/prestamos.php" class="nav-link text-white"><i class="fas fa-archive me-2"></i>Préstamos</a></li>
                        <li class="nav-item"><a href="../../../paginas/devoluciones/devoluciones.php" class="nav-link text-white"><i class="fas fa-undo me-2"></i>Devoluciones</a></li>
                        <li class="nav-item"><a href="../../../paginas/productos/listado-productos.php" class="nav-link text-white"><i class="fas fa-shopping-cart me-2"></i>Productos</a></li>
                        <li class="nav-item"><a href="../../../paginas/roles/listado-roles.php" class="nav-link text-white"><i class="fas fa-user-shield me-2"></i>Rol</a></li>
                        <li class="nav-item"><a href="../../../paginas/roles/gestion-permisos/gestion-permisos.php" class="nav-link active text-white"><i class="fas fa-key me-2"></i>Permisos</a></li>
                        <li class="nav-item"><a href="../../../../paginas/recursos/estado-recursos/estado-recursos.php" class="nav-link text-white"><i class="fas fa-chart-line me-2"></i>Estado de Recursos</a></li>
                        <li class="nav-item"><a href="../../../paginas/productos/ingreso-productos/ingreso-productos.php" class="nav-link text-white"><i class="fas fa-arrow-up me-2"></i>Ingresos</a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <hr class="text-secondary">
        <a href="../../../index.php" class="btn btn-danger w-100">Cerrar sesión</a>
    </div>


    <!-- Contenido principal -->
    <div class="flex-grow-1 p-4 bg-light d-flex flex-column">
        <div>
            <h1 class="h4 mb-2">Gestión de Permisos</h1>
            <hr>
            <p class="mt-1">Llenar los campos para registrar o editar permisos</p>

            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#permisoModal">Agregar Permiso</button>

            <input type="text" id="busquedaPermiso" class="form-control mb-3" placeholder="Buscar por usuario...">

            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Descripción</th>
                        <th>Asignado por</th>
                        <th>Fecha</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody id="permisoTableBody">
                <?php 
                if ($totalPermisos > 0):
                    for ($i = $indiceInicio; $i < $indiceFin; $i++): 
                        $permiso = $_SESSION["permisos"][$i];
                ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= htmlspecialchars($permiso['usuario']) ?></td>
                        <td><?= htmlspecialchars($permiso['rol']) ?></td>
                        <td><?= htmlspecialchars($permiso['descripcion']) ?></td>
                        <td><?= htmlspecialchars($permiso['asignado_por']) ?></td>
                        <td><?= htmlspecialchars($permiso['fecha']) ?></td>
                        <td>
                            <button class="btn btn-icon text-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editPermisoModal"
                                    data-id="<?= $i ?>"
                                    data-usuario="<?= htmlspecialchars($permiso['usuario']) ?>"
                                    data-rol="<?= htmlspecialchars($permiso['rol']) ?>"
                                    data-descripcion="<?= htmlspecialchars($permiso['descripcion']) ?>"
                                    data-asignado="<?= htmlspecialchars($permiso['asignado_por']) ?>"
                                    data-fecha="<?= htmlspecialchars($permiso['fecha']) ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                        <td>
                            <form action="delete-permiso.php" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este permiso?');">
                                <input type="hidden" name="permiso_id" value="<?= $i ?>">
                                <button class="btn btn-icon text-danger"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php 
                    endfor;
                else: ?>
                    <tr><td colspan="8" class="text-center">No hay permisos registrados.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <nav aria-label="Paginación de permisos">
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

<!-- Modal Agregar Permiso -->
<div class="modal fade" id="permisoModal" tabindex="-1" aria-labelledby="permisoModalLabel" aria-hidden="true">
    <form class="modal-dialog" method="POST" action="crear-permiso.php">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Permiso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input class="form-control mb-2" name="usuario" placeholder="Usuario" required>
                <input class="form-control mb-2" name="rol" placeholder="Rol" required>
                <textarea class="form-control mb-2" name="descripcion" placeholder="Descripción" required></textarea>
                <input class="form-control mb-2" name="asignado_por" placeholder="Asignado por" required>
                <input type="date" class="form-control mb-2" name="fecha" required>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>

<!-- Modal Editar Permiso -->
<div class="modal fade" id="editPermisoModal" tabindex="-1" aria-labelledby="editPermisoModalLabel" aria-hidden="true">
    <form class="modal-dialog" method="POST" action="editar-permiso.php">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Permiso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="permiso_id" id="editPermisoId">
                <input class="form-control mb-2" name="usuario" id="editPermisoUsuario" required>
                <input class="form-control mb-2" name="rol" id="editPermisoRol" required>
                <textarea class="form-control mb-2" name="descripcion" id="editPermisoDescripcion" required></textarea>
                <input class="form-control mb-2" name="asignado_por" id="editPermisoAsignado" required>
                <input type="date" class="form-control mb-2" name="fecha" id="editPermisoFecha" required>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </form>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const editModal = document.getElementById('editPermisoModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('editPermisoId').value = button.getAttribute('data-id');
        document.getElementById('editPermisoUsuario').value = button.getAttribute('data-usuario');
        document.getElementById('editPermisoRol').value = button.getAttribute('data-rol');
        document.getElementById('editPermisoDescripcion').value = button.getAttribute('data-descripcion');
        document.getElementById('editPermisoAsignado').value = button.getAttribute('data-asignado');
        document.getElementById('editPermisoFecha').value = button.getAttribute('data-fecha');
    });

    document.getElementById('busquedaPermiso').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        document.querySelectorAll('#permisoTableBody tr').forEach(row => {
            const usuario = row.children[1].textContent.toLowerCase();
            row.style.display = usuario.includes(filtro) ? '' : 'none';
        });
    });
</script>
</body>
</html>