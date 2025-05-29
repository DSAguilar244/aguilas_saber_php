<?php
session_start();

if (!isset($_SESSION["prestamos"])) {
    $_SESSION["prestamos"] = [];
}

// Paginación
$porPagina = 5;
$totalPrestamos = count($_SESSION["prestamos"]);
$totalPaginas = $totalPrestamos > 0 ? ceil($totalPrestamos / $porPagina) : 1;

$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$paginaActual = max(1, min($paginaActual, $totalPaginas));

$inicio = ($paginaActual - 1) * $porPagina;
$fin = min($inicio + $porPagina, $totalPrestamos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Préstamos</title>
    <link rel="icon" href="../../../static/img/fondo_aguilas_saber.png" type="image/x-icon">
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
                        <li class="nav-item"><a href="../../paginas/usuarios/listado-usuarios.php" class="nav-link text-white"><i class="fas fa-users me-2"></i>Usuarios</a></li>
                        <li class="nav-item"><a href="../../paginas/recursos/recursos.php" class="nav-link text-white"><i class="fas fa-boxes me-2"></i>Recursos</a></li>
                        <li class="nav-item"><a href="../../paginas/prestamos/prestamos.php" class="nav-link active text-white"><i class="fas fa-archive me-2"></i>Préstamos</a></li>
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

    <div class="flex-grow-1 p-4 bg-light d-flex flex-column">
        <div>
            <h1 class="h4 mb-2">Gestión de Préstamos</h1>
            <hr>
            <p>Llene los campos para registrar o editar un préstamo</p>

            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#prestamoModal">Agregar Préstamo</button>
            <input type="text" id="busquedaPrestamo" class="form-control mb-3" placeholder="Buscar por solicitante...">

            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Solicitante</th>
                        <th>Tipo</th>
                        <th>Artículo</th>
                        <th>Fecha de Préstamo</th>
                        <th>Fecha de Devolución</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="prestamoTableBody">
                <?php if ($totalPrestamos > 0):
                    for ($i = $inicio; $i < $fin; $i++):
                        $p = $_SESSION["prestamos"][$i];
                ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= htmlspecialchars($p["solicitante"]) ?></td>
                        <td><?= htmlspecialchars($p["tipo"]) ?></td>
                        <td><?= htmlspecialchars($p["articulo"]) ?></td>
                        <td><?= htmlspecialchars($p["fecha_prestamo"]) ?></td>
                        <td><?= htmlspecialchars($p["fecha_devolucion"]) ?></td>
                        <td><?= htmlspecialchars($p["estado"]) ?></td>
                        <td>
                            <button class="btn btn-icon text-primary" data-bs-toggle="modal" data-bs-target="#editPrestamoModal"
                                data-id="<?= $i ?>"
                                data-solicitante="<?= htmlspecialchars($p["solicitante"]) ?>"
                                data-tipo="<?= htmlspecialchars($p["tipo"]) ?>"
                                data-articulo="<?= htmlspecialchars($p["articulo"]) ?>"
                                data-fecha_prestamo="<?= htmlspecialchars($p["fecha_prestamo"]) ?>"
                                data-fecha_devolucion="<?= htmlspecialchars($p["fecha_devolucion"]) ?>"
                                data-estado="<?= htmlspecialchars($p["estado"]) ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="delete.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Deseas eliminar este préstamo?');">
                                <input type="hidden" name="prestamo_id" value="<?= $i ?>">
                                <button class="btn btn-icon text-danger"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endfor; else: ?>
                    <tr><td colspan="8" class="text-center">No hay préstamos registrados.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <nav>
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

<!-- Modal Crear -->
<div class="modal fade" id="prestamoModal" tabindex="-1">
    <form class="modal-dialog" method="POST" action="crear.php">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Préstamo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input name="solicitante" class="form-control mb-2" placeholder="Solicitante" required>
                <input name="tipo" class="form-control mb-2" placeholder="Tipo" required>
                <input name="articulo" class="form-control mb-2" placeholder="Artículo" required>
                <input name="fecha_prestamo" type="date" class="form-control mb-2" required>
                <input name="fecha_devolucion" type="date" class="form-control mb-2" required>
                <select name="estado" class="form-control mb-2" required>
                    <option value="Prestado">Prestado</option>
                    <option value="Devuelto">Devuelto</option>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editPrestamoModal" tabindex="-1">
    <form class="modal-dialog" method="POST" action="editar.php">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Préstamo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="prestamo_id" id="editId">
                <input name="solicitante" id="editSolicitante" class="form-control mb-2" required>
                <input name="tipo" id="editTipo" class="form-control mb-2" required>
                <input name="articulo" id="editArticulo" class="form-control mb-2" required>
                <input type="date" name="fecha_prestamo" id="editFechaPrestamo" class="form-control mb-2" required>
                <input type="date" name="fecha_devolucion" id="editFechaDevolucion" class="form-control mb-2" required>
                <select name="estado" id="editEstado" class="form-control mb-2" required>
                    <option value="Prestado">Prestado</option>
                    <option value="Devuelto">Devuelto</option>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </form>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('editPrestamoModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    document.getElementById('editId').value = button.getAttribute('data-id');
    document.getElementById('editSolicitante').value = button.getAttribute('data-solicitante');
    document.getElementById('editTipo').value = button.getAttribute('data-tipo');
    document.getElementById('editArticulo').value = button.getAttribute('data-articulo');
    document.getElementById('editFechaPrestamo').value = button.getAttribute('data-fecha_prestamo');
    document.getElementById('editFechaDevolucion').value = button.getAttribute('data-fecha_devolucion');
    document.getElementById('editEstado').value = button.getAttribute('data-estado');
});

document.getElementById('busquedaPrestamo').addEventListener('input', function () {
    const filtro = this.value.toLowerCase();
    document.querySelectorAll('#prestamoTableBody tr').forEach(row => {
        const solicitante = row.children[1].textContent.toLowerCase();
        row.style.display = solicitante.includes(filtro) ? '' : 'none';
    });
});
</script>
</body>
</html>