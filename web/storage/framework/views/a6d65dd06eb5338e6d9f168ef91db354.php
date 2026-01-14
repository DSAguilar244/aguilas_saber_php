

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(asset('css/usuario.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Roles del Sistema</h2>
    <p class="text-muted">Gestiona los permisos de los roles existentes</p>

    <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    
    <div class="mb-3">
        <input type="text" id="search-roles" class="form-control" placeholder="Buscar por nombre o descripción..." autocomplete="off">
    </div>

    
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="roles-body">
            <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td data-label="Nombre"><?php echo e($role->name); ?></td> 
                <td data-label="Descripción"><?php echo e($role->descripcion); ?></td>
                <td data-label="Acciones">
                    <a href="<?php echo e(route('roles.edit', $role)); ?>" class="btn btn-warning btn-sm w-auto">
                        <i class="fas fa-edit"></i> Editar Permisos
                    </a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="3" class="text-center text-muted">No se han encontrado resultados.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    
    <div class="d-flex justify-content-center" id="roles-paginacion">
        <?php echo e($roles->links('vendor.pagination.custom')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-roles');
    const tableBody = document.getElementById('roles-body');
    const paginacion = document.getElementById('roles-paginacion');
    let timer = null;

    input.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(timer);

        paginacion.style.display = query ? 'none' : 'block';

        timer = setTimeout(() => {
            fetch(`/roles/buscar?search=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';

                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No se encontraron roles.</td></tr>';
                        return;
                    }

                    data.forEach(role => {
                        tableBody.innerHTML += `
                            <tr>
                                <td data-label="Nombre">${role.name}</td> <!-- ✅ corregido -->
                                <td data-label="Descripción">${role.descripcion ?? ''}</td>
                                <td data-label="Acciones">
                                    <a href="/roles/${role.id}/edit" class="btn btn-warning btn-sm w-auto"><i class="fas fa-edit"></i> Editar</a>
                                    <form method="POST" action="/roles/${role.id}" style="display:inline-block;">
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Dev-proyectos\aguilas_mobile_react-main\aguilas_saber_php\web\resources\views/roles/index.blade.php ENDPATH**/ ?>