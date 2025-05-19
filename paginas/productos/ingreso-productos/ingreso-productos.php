<?php
session_start();

if (!isset($_SESSION["ingresos"])) {
    $_SESSION["ingresos"] = [];
}

$ingresosPorPagina = 20;
$totalIngresos = count($_SESSION["ingresos"]);

$totalPaginas = max(1, ceil($totalIngresos / $ingresosPorPagina));
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaActual < 1) $paginaActual = 1;
if ($paginaActual > $totalPaginas) $paginaActual = $totalPaginas;

$inicio = ($paginaActual - 1) * $ingresosPorPagina;
$ingresosPagina = array_slice($_SESSION["ingresos"], $inicio, $ingresosPorPagina);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Ingreso de Productos</title>
    <link rel="icon" href="../../../static/img/fondo_aguilas_saber.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="ingreso_productos.css" />
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3" id="sidebar" style="min-width: 250px;">
        <div class="text-center mb-4">
            <img src="../../../static/img/fondo_aguilas_saber.png" alt="Logo Maky System" class="img-fluid mb-2" style="max-height: 80px;" />
            <h4>Maky System</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="../../../paginas/pagina_inicio/dashboard.html" class="nav-link text-white"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="../../../paginas/pagina_inicio/home.php" class="nav-link text-white"><i class="fas fa-home me-2"></i>Inicio</a>
            </li>
            <li class="nav-item">
                <a href="#adminSubmenu" class="nav-link text-white" data-bs-toggle="collapse" aria-expanded="false"><i class="fas fa-cogs me-2"></i>Administración <i class="fas fa-chevron-down ms-auto"></i></a>
                <div class="collapse" id="adminSubmenu">
                    <ul class="nav flex-column ms-3">
                       <li class="nav-item"><a href="../../../paginas/usuarios/listado-usuarios.php" class="nav-link text-white"><i class="fas fa-users me-2"></i>Usuarios</a></li>
                        <li class="nav-item"><a href="../../../paginas/recursos/recursos.php" class="nav-link text-white"><i class="fas fa-boxes me-2"></i>Recursos</a></li>
                        <li class="nav-item"><a href="../../../paginas/prestamos/prestamos.php" class="nav-link text-white"><i class="fas fa-archive me-2"></i>Préstamos</a></li>
                        <li class="nav-item"><a href="../../../paginas/devoluciones/devoluciones.php" class="nav-link text-white"><i class="fas fa-undo me-2"></i>Devoluciones</a></li>
                        <li class="nav-item"><a href="../../../paginas/productos/listado-productos.php" class="nav-link text-white"><i class="fas fa-shopping-cart me-2"></i>Productos</a></li>
                        <li class="nav-item"><a href="../../../paginas/roles/listado-roles.php" class="nav-link text-white"><i class="fas fa-user-shield me-2"></i>Rol</a></li>
                        <li class="nav-item"><a href="../../../paginas/roles/gestion-permisos/gestion-permisos.php" class="nav-link text-white"><i class="fas fa-key me-2"></i>Permisos</a></li>
                        <li class="nav-item"><a href="../../../../paginas/recursos/estado-recursos/estado-recursos.php" class="nav-link text-white"><i class="fas fa-chart-line me-2"></i>Estado de Recursos</a></li>
                        <li class="nav-item"><a href="../../../paginas/productos/ingreso-productos/ingreso-productos.php" class="nav-link active text-white"><i class="fas fa-arrow-up me-2"></i>Ingresos</a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <hr class="text-secondary" />
        <a href="../../../index.html" class="btn btn-danger w-100">Cerrar sesión</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4 bg-light d-flex flex-column" style="min-height: 100vh;">
        <div>
            <h1 class="h4 mb-2">Ingreso de Productos</h1>
            <hr />
            <p>Llene los campos para registrar un nuevo ingreso</p>

            <!-- Botón para nuevo ingreso -->
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#ingresoModal">Registrar Ingreso</button>

            <!-- Barra de búsqueda -->
            <input type="text" id="busquedaProducto" class="form-control mb-3" placeholder="Buscar producto por nombre..." />

            <!-- Tabla -->
            <table class="table table-striped" id="tablaIngresos">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Fecha de Ingreso</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Usuario de Registro</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($ingresosPagina) === 0): ?>
                        <tr><td colspan="7" class="text-center">No hay ingresos registrados aún</td></tr>
                    <?php else: ?>
                        <?php foreach ($ingresosPagina as $index => $ingreso): ?>
                            <tr>
                                <td><?= $inicio + $index + 1 ?></td>
                                <td><?= htmlspecialchars($ingreso['fecha']) ?></td>
                                <td class="producto-nombre"><?= htmlspecialchars($ingreso['producto']) ?></td>
                                <td><?= htmlspecialchars($ingreso['cantidad']) ?></td>
                                <td><?= htmlspecialchars($ingreso['usuario']) ?></td>
                                <td>
                                    <button class="btn btn-icon text-primary" data-bs-toggle="modal"
                                            data-bs-target="#editIngresoModal"
                                            data-id="<?= $inicio + $index ?>"
                                            data-fecha="<?= htmlspecialchars($ingreso['fecha']) ?>"
                                            data-producto="<?= htmlspecialchars($ingreso['producto']) ?>"
                                            data-cantidad="<?= htmlspecialchars($ingreso['cantidad']) ?>"
                                            data-usuario="<?= htmlspecialchars($ingreso['usuario']) ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td>
                                    <form action="delete-ingreso.php" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este ingreso?');">
                                        <input type="hidden" name="ingreso_id" value="<?= $inicio + $index ?>">
                                        <button class="btn btn-icon text-danger"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <nav aria-label="Paginación">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $paginaActual - 1 ?>">Anterior</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?= $i === $paginaActual ? 'active' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
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

<!-- Modal Agregar Ingreso -->
<div class="modal fade" id="ingresoModal" tabindex="-1" aria-labelledby="ingresoModalLabel" aria-hidden="true">
    <form class="modal-dialog" method="POST" action="crear-ingreso.php">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input class="form-control mb-2" type="date" name="fecha" required />
                <input class="form-control mb-2" name="producto" placeholder="Nombre del producto" required />
                <input class="form-control mb-2" type="number" name="cantidad" min="1" required />
                <input class="form-control mb-2" name="usuario" placeholder="Usuario que registra" required />
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>

<!-- Modal Editar Ingreso -->
<div class="modal fade" id="editIngresoModal" tabindex="-1" aria-labelledby="editIngresoModalLabel" aria-hidden="true">
    <form class="modal-dialog" method="POST" action="editar-ingreso.php">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="ingreso_id" id="editIngresoId" />
                <input class="form-control mb-2" type="date" name="fecha" id="editIngresoFecha" required />
                <input class="form-control mb-2" name="producto" id="editIngresoProducto" required />
                <input class="form-control mb-2" type="number" name="cantidad" id="editIngresoCantidad" min="1" required />
                <input class="form-control mb-2" name="usuario" id="editIngresoUsuario" required />
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Modal Editar
    const editModal = document.getElementById('editIngresoModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('editIngresoId').value = button.getAttribute('data-id');
        document.getElementById('editIngresoFecha').value = button.getAttribute('data-fecha');
        document.getElementById('editIngresoProducto').value = button.getAttribute('data-producto');
        document.getElementById('editIngresoCantidad').value = button.getAttribute('data-cantidad');
        document.getElementById('editIngresoUsuario').value = button.getAttribute('data-usuario');
    });

    // Búsqueda en tabla
    document.getElementById('busquedaProducto').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaIngresos tbody tr');
        filas.forEach(fila => {
            const nombre = fila.querySelector('.producto-nombre')?.textContent.toLowerCase() || "";
            fila.style.display = nombre.includes(filtro) ? '' : 'none';
        });
    });
</script>
</body>
</html>