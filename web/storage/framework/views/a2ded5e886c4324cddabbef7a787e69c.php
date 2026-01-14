<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="Pagination Navigation" class="custom-pagination-wrapper">
        <div class="custom-pagination">
            
            <?php if($paginator->onFirstPage()): ?>
                <span class="custom-page-btn disabled" aria-disabled="true">
                    <i class="fas fa-angles-left"></i>
                </span>
                <span class="custom-page-btn disabled" aria-disabled="true">
                    <i class="fas fa-angle-left"></i>
                </span>
            <?php else: ?>
                <a href="<?php echo e($paginator->url(1)); ?>" class="custom-page-btn" rel="prev" aria-label="Primera página">
                    <i class="fas fa-angles-left"></i>
                </a>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="custom-page-btn" rel="prev" aria-label="Anterior">
                    <i class="fas fa-angle-left"></i>
                </a>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <span class="custom-page-dots"><?php echo e($element); ?></span>
                <?php endif; ?>

                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <span class="custom-page-btn active" aria-current="page"><?php echo e($page); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>" class="custom-page-btn"><?php echo e($page); ?></a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="custom-page-btn" rel="next" aria-label="Siguiente">
                    <i class="fas fa-angle-right"></i>
                </a>
                <a href="<?php echo e($paginator->url($paginator->lastPage())); ?>" class="custom-page-btn" aria-label="Última página">
                    <i class="fas fa-angles-right"></i>
                </a>
            <?php else: ?>
                <span class="custom-page-btn disabled" aria-disabled="true">
                    <i class="fas fa-angle-right"></i>
                </span>
                <span class="custom-page-btn disabled" aria-disabled="true">
                    <i class="fas fa-angles-right"></i>
                </span>
            <?php endif; ?>
        </div>

        
        <div class="custom-pagination-info">
            Mostrando <?php echo e($paginator->firstItem()); ?> - <?php echo e($paginator->lastItem()); ?> de <?php echo e($paginator->total()); ?> resultados
        </div>
    </nav>
<?php endif; ?>
<?php /**PATH E:\Dev-proyectos\aguilas_mobile_react-main\aguilas_saber_php\web\resources\views/vendor/pagination/custom.blade.php ENDPATH**/ ?>