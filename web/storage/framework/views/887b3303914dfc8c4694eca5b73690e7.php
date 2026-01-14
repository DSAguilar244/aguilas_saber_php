

<?php $__env->startSection('content'); ?>
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-history text-danger me-2"></i>
                Auditoría del Sistema
            </h2>
            <p class="text-muted mb-0">
                <small>Registro completo de todas las acciones realizadas en el sistema</small>
            </p>
        </div>
        <?php if(auth()->user()->roles->contains('name', 'admin')): ?>
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalLimpiarAuditoria">
            <i class="fas fa-trash-alt me-2"></i>Limpiar Auditoría
        </button>
        <?php endif; ?>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body p-4">
            <form method="GET" class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="far fa-calendar me-1"></i>Mes
                    </label>
                    <select name="mes" class="form-select">
                        <option value="">Todos los meses</option>
                        <?php
                            $mesesEspanol = [
                                '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                                '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                                '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                            ];
                        ?>
                        <?php $__currentLoopData = $mesesEspanol; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($num); ?>" <?php if(request('mes')==$num): echo 'selected'; endif; ?>><?php echo e($nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="far fa-calendar-alt me-1"></i>Año
                    </label>
                    <select name="anio" class="form-select">
                        <option value="">Todos los años</option>
                        <?php $__currentLoopData = range(2026, 2030); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($y); ?>" <?php if(request('anio')==$y): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-layer-group me-1"></i>Gestión
                    </label>
                    <select name="entidad" class="form-select">
                        <option value="">Todas las gestiones</option>
                        <option value="usuarios" <?php if(request('entidad')=='usuarios'): echo 'selected'; endif; ?>>
                            <i class="fas fa-users"></i> Usuarios
                        </option>
                        <option value="productos" <?php if(request('entidad')=='productos'): echo 'selected'; endif; ?>>
                            <i class="fas fa-shopping-cart"></i> Productos
                        </option>
                        <option value="recursos" <?php if(request('entidad')=='recursos'): echo 'selected'; endif; ?>>
                            <i class="fas fa-database"></i> Recursos
                        </option>
                        <option value="prestamos" <?php if(request('entidad')=='prestamos'): echo 'selected'; endif; ?>>
                            <i class="fas fa-archive"></i> Préstamos
                        </option>
                        <option value="roles" <?php if(request('entidad')=='roles'): echo 'selected'; endif; ?>>
                            <i class="fas fa-user-shield"></i> Roles
                        </option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 d-flex align-items-end gap-2">
                    <button class="btn btn-danger w-50" type="submit">
                        <i class="fas fa-search me-1"></i>Filtrar
                    </button>
                    <a href="<?php echo e(route('auditorias.index')); ?>" class="btn btn-outline-secondary w-50">
                        <i class="fas fa-redo me-1"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <?php if($auditorias->count()): ?>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="badge bg-danger fs-6">
                <i class="fas fa-list me-1"></i><?php echo e($auditorias->total()); ?> registros encontrados
            </span>
        </div>
        <div class="text-muted">
            <small>Mostrando página <?php echo e($auditorias->currentPage()); ?> de <?php echo e($auditorias->lastPage()); ?></small>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="px-3">
                            <i class="far fa-clock me-1"></i>Fecha y Hora
                        </th>
                        <th>
                            <i class="fas fa-user me-1"></i>Usuario
                        </th>
                        <th>
                            <i class="fas fa-layer-group me-1"></i>Gestión
                        </th>
                        <th>
                            <i class="fas fa-bolt me-1"></i>Acción
                        </th>
                        <th class="text-center">
                            <i class="fas fa-hashtag me-1"></i>ID
                        </th>
                        <th>
                            <i class="fas fa-info-circle me-1"></i>Detalles
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $auditorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aud): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-3">
                            <div class="d-flex flex-column">
                                <span class="fw-semibold"><?php echo e($aud->created_at->format('d/m/Y')); ?></span>
                                <small class="text-muted"><?php echo e($aud->created_at->format('H:i:s')); ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle text-secondary me-2"></i>
                                <div>
                                    <div class="fw-semibold"><?php echo e($aud->usuario_nombre ?? 'Usuario'); ?></div>
                                    <small class="text-muted"><?php echo e($aud->usuario_email ?? '—'); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php
                                $iconos = [
                                    'usuarios' => 'fa-users text-primary',
                                    'productos' => 'fa-shopping-cart text-danger',
                                    'recursos' => 'fa-database text-info',
                                    'prestamos' => 'fa-archive text-success',
                                    'roles' => 'fa-user-shield text-warning'
                                ];
                                $icono = $iconos[$aud->entidad] ?? 'fa-circle text-secondary';
                            ?>
                            <span class="badge bg-light text-dark border">
                                <i class="fas <?php echo e($icono); ?> me-1"></i>
                                <?php echo e(ucfirst($aud->entidad)); ?>

                            </span>
                        </td>
                        <td>
                            <?php
                                $acciones = [
                                    'crear' => ['bg' => 'success', 'icon' => 'plus-circle'],
                                    'actualizar' => ['bg' => 'warning', 'icon' => 'edit'],
                                    'eliminar' => ['bg' => 'danger', 'icon' => 'trash-alt']
                                ];
                                $accion = $acciones[strtolower($aud->accion)] ?? ['bg' => 'secondary', 'icon' => 'circle'];
                            ?>
                            <span class="badge bg-<?php echo e($accion['bg']); ?>">
                                <i class="fas fa-<?php echo e($accion['icon']); ?> me-1"></i>
                                <?php echo e(ucfirst($aud->accion)); ?>

                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary"><?php echo e($aud->registro_id ?? '—'); ?></span>
                        </td>
                        <td>
                            <?php
                                $det = $aud->detalles;
                                $text = $det;
                                if ($det && $decoded = json_decode($det, true)) {
                                    $text = collect($decoded)->filter(fn($v)=>is_scalar($v))->map(function($v,$k){return ucfirst($k).': '.$v;})->implode(', ');
                                }
                            ?>
                            <small class="text-muted"><?php echo e(Str::limit($text ?? 'Sin detalles', 50)); ?></small>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($auditorias->links('vendor.pagination.custom')); ?>

    </div>
    <?php else: ?>
        
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No hay registros de auditoría</h4>
                <p class="text-muted mb-0">
                    No se encontraron registros con los filtros aplicados.
                    <?php if(request()->hasAny(['mes', 'anio', 'entidad'])): ?>
                        <br><a href="<?php echo e(route('auditorias.index')); ?>" class="btn btn-sm btn-outline-danger mt-3">
                            <i class="fas fa-redo me-1"></i>Limpiar filtros
                        </a>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="modalLimpiarAuditoria" tabindex="-1" aria-labelledby="modalLimpiarAuditoriaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalLimpiarAuditoriaLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Limpieza de Auditoría
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                <h5 class="mb-3">¿Estás seguro de eliminar TODOS los registros de auditoría?</h5>
                <p class="text-muted mb-0">
                    Esta acción eliminará permanentemente todos los registros del historial de auditoría del sistema.
                    <br><strong class="text-danger">Esta acción no se puede deshacer.</strong>
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <form action="<?php echo e(route('auditorias.limpiar')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i>Sí, Limpiar Datos
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Dev-proyectos\aguilas_mobile_react-main\aguilas_saber_php\web\resources\views/auditorias/index.blade.php ENDPATH**/ ?>