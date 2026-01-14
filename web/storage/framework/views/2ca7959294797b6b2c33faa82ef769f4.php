

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(asset('css/usuario.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Préstamos</h2>

    <?php
        $isAdmin = auth()->user()->roles->contains('name', 'admin');
        $canCreate = $isAdmin || auth()->user()->hasPermissionTo('prestamos.crear');
        $canEdit = $isAdmin || auth()->user()->hasPermissionTo('prestamos.editar');
        $canDelete = $isAdmin || auth()->user()->hasPermissionTo('prestamos.eliminar');
    ?>

    <?php if($canCreate): ?>
    <a href="<?php echo e(route('prestamos.create')); ?>" class="btn btn-primary mb-3">Agregar Préstamo</a>
    <?php endif; ?>

    <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    
    <div class="mb-3">
        <input type="text" id="search-prestamos" class="form-control" placeholder="Buscar por código, usuario, recurso...">
    </div>

    
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Usuario</th>
                <th>Recurso</th>
                <th>Fecha Préstamo</th>
                <th>Fecha Devolución</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="prestamos-body">
            <?php $__empty_1 = true; $__currentLoopData = $prestamos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prestamo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td data-label="Código"><?php echo e($prestamo->codigo); ?></td>
                <td data-label="Usuario"><?php echo e($prestamo->usuario->nombre ?? ''); ?> <?php echo e($prestamo->usuario->apellido ?? ''); ?></td>
                <td data-label="Recurso"><?php echo e($prestamo->recurso->nombre ?? ''); ?></td>
                <td data-label="Fecha Préstamo"><?php echo e($prestamo->fecha_prestamo); ?></td>
                <td data-label="Fecha Devolución"><?php echo e($prestamo->fecha_devolucion); ?></td>
                <td data-label="Estado">
                    <?php if($prestamo->estado === 'Activo'): ?>
                        <span class="badge badge-disponible">Activo</span>
                    <?php else: ?>
                        <span class="badge badge-no-disponible"><?php echo e($prestamo->estado); ?></span>
                    <?php endif; ?>
                </td>
                <td data-label="Acciones">
                    <?php if($canEdit): ?>
                    <a href="<?php echo e(route('prestamos.edit', $prestamo)); ?>" class="btn btn-warning btn-sm w-auto"><i class="fas fa-edit"></i> Editar</a>
                    <?php endif; ?>
                    
                    <?php if($canDelete): ?>
                    <button type="button" class="btn btn-danger btn-sm w-auto" data-bs-toggle="modal" data-bs-target="#modalEliminar<?php echo e($prestamo->id); ?>">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                    <?php endif; ?>
                    
                    
                    <div class="modal fade" id="modalEliminar<?php echo e($prestamo->id); ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center py-4">
                                    <i class="fas fa-archive fa-4x text-danger mb-3"></i>
                                    <h5 class="mb-3">¿Estás seguro de eliminar este préstamo?</h5>
                                    <p class="text-muted mb-0">
                                        <strong>Código: <?php echo e($prestamo->codigo); ?></strong><br>
                                        <small>Usuario: <?php echo e($prestamo->usuario->nombre ?? ''); ?> <?php echo e($prestamo->usuario->apellido ?? ''); ?></small><br>
                                        <small>Recurso: <?php echo e($prestamo->recurso->nombre ?? ''); ?></small>
                                    </p>
                                    <p class="text-danger mt-2"><small>Esta acción no se puede deshacer.</small></p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>Cancelar
                                    </button>
                                    <form action="<?php echo e(route('prestamos.destroy', $prestamo)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash me-1"></i>Sí, Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="text-center text-muted">No se han encontrado resultados.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    
    <div class="d-flex justify-content-center" id="prestamos-paginacion">
        <?php echo e($prestamos->links('vendor.pagination.custom')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-prestamos');
    const tableBody = document.getElementById('prestamos-body');
    const paginacion = document.getElementById('prestamos-paginacion');
    let timer = null;

    input.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(timer);

        if (!query) {
            paginacion.style.display = 'block';
            return;
        }

        paginacion.style.display = 'none';

        timer = setTimeout(() => {
            fetch(`/prestamos/buscar?search=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';

                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No se encontraron préstamos.</td></tr>';
                        return;
                    }

                    data.forEach(prestamo => {
                        const estadoBadge = prestamo.estado === 'Activo'
                            ? '<span class="badge badge-disponible">Activo</span>'
                            : `<span class="badge badge-no-disponible">${prestamo.estado}</span>`;

                        tableBody.innerHTML += `
                            <tr>
                                <td data-label="Código">${prestamo.codigo}</td>
                                <td data-label="Usuario">${prestamo.usuario}</td>
                                <td data-label="Recurso">${prestamo.recurso}</td>
                                <td data-label="Fecha Préstamo">${prestamo.fecha_prestamo}</td>
                                <td data-label="Fecha Devolución">${prestamo.fecha_devolucion}</td>
                                <td data-label="Estado">${estadoBadge}</td>
                                <td data-label="Acciones">
                                    <a href="/prestamos/${prestamo.id}/edit" class="btn btn-warning btn-sm w-auto"><i class="fas fa-edit"></i> Editar</a>
                                    <form method="POST" action="/prestamos/${prestamo.id}" style="display:inline-block;">
                                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm w-auto"><i class="fas fa-trash"></i> Eliminar</button>
                                    </form>
                                </td>
                            </tr>`;
                    });
                });
        }, 400);
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Dev-proyectos\aguilas_mobile_react-main\aguilas_saber_php\web\resources\views/prestamos/index.blade.php ENDPATH**/ ?>