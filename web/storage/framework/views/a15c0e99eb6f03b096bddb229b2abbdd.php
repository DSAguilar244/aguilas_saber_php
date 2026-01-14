

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(asset('css/usuario.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Usuarios</h2>

    <?php if(auth()->user()->roles->contains('name', 'admin')): ?>
    <a href="<?php echo e(route('usuarios.create')); ?>" class="btn btn-primary mb-3">Agregar Usuario</a>
    <?php endif; ?>

    <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <input type="text" id="search-usuarios" class="form-control"
               placeholder="Buscar por nombre, apellido o correo..." autocomplete="off">
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Activo</th>
                <th>Roles</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="usuarios-body">
            <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td data-label="Nombre"><?php echo e($usuario->nombre); ?></td>
                <td data-label="Apellido"><?php echo e($usuario->apellido); ?></td>
                <td data-label="Email"><?php echo e($usuario->email); ?></td>
                <td data-label="Teléfono"><?php echo e($usuario->telefono); ?></td>
                <td data-label="Activo"><?php echo e($usuario->activo ? 'Sí' : 'No'); ?></td>
                <td data-label="Roles">
                    <?php $__empty_2 = true; $__currentLoopData = $usuario->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                    <span class="badge bg-info"><?php echo e($rol->name); ?></span> 
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                    <span class="badge badge-no-disponible">Sin rol</span>
                    <?php endif; ?>
                </td>
                <td data-label="Acciones">
                    <?php
                        $isAdmin = auth()->user()->roles->contains('name', 'admin');
                        $canEdit = $isAdmin || auth()->user()->hasPermissionTo('usuarios.editar');
                        $canDelete = $isAdmin || auth()->user()->hasPermissionTo('usuarios.eliminar');
                    ?>
                    
                    <?php if($canEdit): ?>
                    <a href="<?php echo e(route('usuarios.edit', $usuario)); ?>" class="btn btn-warning btn-sm w-auto"><i class="fas fa-edit"></i> Editar</a>
                    <?php endif; ?>
                    
                    <?php if($canDelete && auth()->id() !== $usuario->id): ?>
                        <button type="button" class="btn btn-danger btn-sm w-auto" data-bs-toggle="modal" data-bs-target="#modalEliminar<?php echo e($usuario->id); ?>">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                            
                        
                        <div class="modal fade" id="modalEliminar<?php echo e($usuario->id); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center py-4">
                                        <i class="fas fa-user-times fa-4x text-danger mb-3"></i>
                                        <h5 class="mb-3">¿Estás seguro de eliminar este usuario?</h5>
                                        <p class="text-muted mb-0">
                                            <strong><?php echo e($usuario->nombre); ?> <?php echo e($usuario->apellido); ?></strong><br>
                                            <?php echo e($usuario->email); ?>

                                        </p>
                                        <p class="text-danger mt-2"><small>Esta acción no se puede deshacer.</small></p>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i>Cancelar
                                        </button>
                                        <form action="<?php echo e(route('usuarios.destroy', $usuario)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash me-1"></i>Sí, Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="text-center text-muted">No se han encontrado resultados.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="d-flex justify-content-center" id="usuarios-paginacion">
        <?php echo e($usuarios->links('vendor.pagination.custom')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-usuarios');
    const tableBody = document.getElementById('usuarios-body');
    const paginacion = document.getElementById('usuarios-paginacion');
    let timer = null;

    input.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(timer);

        paginacion.style.display = query ? 'none' : 'block';

        timer = setTimeout(() => {
            fetch(`/usuarios/buscar?search=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';

                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No se encontraron usuarios.</td></tr>';
                        return;
                    }

                    data.forEach(usuario => {
                        const rolesHTML = usuario.roles.length > 0
                            ? usuario.roles.map(r => `<span class="badge bg-info">${r.name}</span>`).join(' ') // 🔄 Corregido
                            : `<span class="badge badge-no-disponible">Sin rol</span>`;

                        tableBody.innerHTML += `
                            <tr>
                                <td data-label="Nombre">${usuario.nombre}</td>
                                <td data-label="Apellido">${usuario.apellido}</td>
                                <td data-label="Email">${usuario.email}</td>
                                <td data-label="Teléfono">${usuario.telefono}</td>
                                <td data-label="Activo">${usuario.activo ? 'Sí' : 'No'}</td>
                                <td data-label="Roles">${rolesHTML}</td>
                                <td data-label="Acciones">
                                    <a href="/usuarios/${usuario.id}/edit" class="btn btn-warning btn-sm w-auto"><i class="fas fa-edit"></i> Editar</a>
                                    <form method="POST" action="/usuarios/${usuario.id}" style="display:inline-block;">
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Dev-proyectos\aguilas_mobile_react-main\aguilas_saber_php\web\resources\views/usuarios/index.blade.php ENDPATH**/ ?>