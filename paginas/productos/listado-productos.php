<?php
session_start();

if (!isset($_SESSION["productos"])) {
    $_SESSION["productos"] = [];
}

// Parámetros para paginación
$productosPorPagina = 20;  // Número de productos por página
$totalProductos = count($_SESSION["productos"]);

$totalPaginas = max(1, ceil($totalProductos / $productosPorPagina));
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaActual < 1) $paginaActual = 1;
if ($paginaActual > $totalPaginas) $paginaActual = $totalPaginas;

// Calcular índice de inicio y obtener slice de productos a mostrar
$inicio = ($paginaActual - 1) * $productosPorPagina;
$productosPagina = array_slice($_SESSION["productos"], $inicio, $productosPorPagina);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Gestión de Productos</title>
    <link rel="icon" href="../../static/img/fondo_aguilas_saber.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="productos_lista.css" rel="stylesheet" />
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3" id="sidebar" style="min-width: 250px;">
        <div class="text-center mb-4">
            <img src="../../static/img/fondo_aguilas_saber.png" alt="Logo Maky System" class="img-fluid mb-2" style="max-height: 80px;" />
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
                        <li class="nav-item"><a href="../../paginas/productos/listado-productos.php" class="nav-link active text-white"><i class="fas fa-shopping-cart me-2"></i>Productos</a></li>
                        <li class="nav-item"><a href="../../paginas/roles/listado-roles.php" class="nav-link text-white"><i class="fas fa-user-shield me-2"></i>Rol</a></li>
                        <li class="nav-item"><a href="../../paginas/roles/gestion-permisos/gestion-permisos.php" class="nav-link text-white"><i class="fas fa-key me-2"></i>Permisos</a></li>
                        <li class="nav-item"><a href="../../paginas/recursos/estado-recursos/estado-recursos.php" class="nav-link text-white"><i class="fas fa-chart-line me-2"></i>Estado de Recursos</a></li>
                        <li class="nav-item"><a href="../../paginas/productos/ingreso-productos/ingreso-productos.php" class="nav-link text-white"><i class="fas fa-arrow-up me-2"></i>Ingresos</a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <hr class="text-secondary" />
        <a href="../../index.html" class="btn btn-danger w-100">Cerrar sesión</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4 bg-light d-flex flex-column" style="min-height: 100vh;">
        <div>
            <h1 class="h4 mb-2">Gestión de Productos</h1>
            <hr style="margin: 0.5rem 0;" />
            <p class="mt-1">Llenar los campos para registrar o editar productos</p>

            <!-- Botón Agregar -->
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#productoModal">Agregar Producto</button>

            <!-- Barra de búsqueda -->
            <input type="text" id="busquedaProducto" class="form-control mb-3" placeholder="Buscar producto por nombre..." />

            <!-- Tabla -->
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre del producto</th>
                        <th>Estado</th>
                        <th>Fecha de Entrada</th>
                        <th>Fecha de Salida</th>
                        <th>Cantidad</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody id="productoTableBody">
<?php if (count($productosPagina) === 0): ?>
    <tr>
        <td colspan="8" class="text-center">No hay productos registrados aún</td>
    </tr>
<?php else: ?>
    <?php foreach ($productosPagina as $index => $producto): ?>
        <tr>
            <td><?= $inicio + $index + 1 ?></td>
            <td><?= htmlspecialchars($producto['nombre']) ?></td>
            <td><?= htmlspecialchars($producto['estado']) ?></td>
            <td><?= htmlspecialchars($producto['fecha_entrada']) ?></td>
            <td><?= htmlspecialchars($producto['fecha_salida']) ?></td>
            <td><?= htmlspecialchars($producto['cantidad']) ?></td>
            <td>
                <button class="btn btn-icon text-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#editProductoModal"
                        data-id="<?= $inicio + $index ?>"
                        data-nombre="<?= htmlspecialchars($producto['nombre']) ?>"
                        data-estado="<?= htmlspecialchars($producto['estado']) ?>"
                        data-fecha_entrada="<?= htmlspecialchars($producto['fecha_entrada']) ?>"
                        data-fecha_salida="<?= htmlspecialchars($producto['fecha_salida']) ?>"
                        data-cantidad="<?= htmlspecialchars($producto['cantidad']) ?>">
                    <i class="fas fa-edit"></i>
                </button>
            </td>
            <td>
                <form action="delete.php" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este producto?');">
                    <input type="hidden" name="producto_id" value="<?= $inicio + $index ?>">
                    <button class="btn btn-icon text-danger"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
</tbody>
            </table>

            <!-- Paginación -->
            <nav aria-label="Paginación de productos">
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

<!-- Modal Agregar Producto -->
<div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
    <form class="modal-dialog" method="POST" action="crear.php">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input class="form-control mb-2" name="nombre_producto" placeholder="Nombre del producto" required />
                <select class="form-control mb-2" name="estado_producto" required>
                    <option value="Disponible">Disponible</option>
                    <option value="No disponible">No disponible</option>
                </select>
                <input class="form-control mb-2" type="date" name="fecha_entrada" required />
                <input class="form-control mb-2" type="date" name="fecha_salida" required />
                <input class="form-control mb-2" type="number" name="cantidad" min="1" required />
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>

<!-- Modal Editar Producto -->
<div class="modal fade" id="editProductoModal" tabindex="-1" aria-labelledby="editProductoModalLabel" aria-hidden="true">
    <form class="modal-dialog" method="POST" action="editar.php">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="producto_id" id="editProductoId" />
                <input class="form-control mb-2" name="nombre_producto" id="editProductoNombre" required />
                <select class="form-control mb-2" name="estado_producto" id="editProductoEstado" required>
                    <option value="Disponible">Disponible</option>
                    <option value="No disponible">No disponible</option>
                </select>
                <input class="form-control mb-2" type="date" name="fecha_entrada" id="editProductoFechaEntrada" required />
                <input class="form-control mb-2" type="date" name="fecha_salida" id="editProductoFechaSalida" required />
                <input class="form-control mb-2" type="number" name="cantidad" id="editProductoCantidad" min="1" required />
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
    const editModal = document.getElementById('editProductoModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('editProductoId').value = button.getAttribute('data-id');
        document.getElementById('editProductoNombre').value = button.getAttribute('data-nombre');
        document.getElementById('editProductoEstado').value = button.getAttribute('data-estado');
        document.getElementById('editProductoFechaEntrada').value = button.getAttribute('data-fecha_entrada');
        document.getElementById('editProductoFechaSalida').value = button.getAttribute('data-fecha_salida');
        document.getElementById('editProductoCantidad').value = button.getAttribute('data-cantidad');
    });

    // Barra de búsqueda (filtra solo los visibles en la página actual)
    document.getElementById('busquedaProducto').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        document.querySelectorAll('#productoTableBody tr').forEach(row => {
            const nombre = row.children[1].textContent.toLowerCase();
            row.style.display = nombre.includes(filtro) ? '' : 'none';
        });
    });
</script>
</body>
</html>