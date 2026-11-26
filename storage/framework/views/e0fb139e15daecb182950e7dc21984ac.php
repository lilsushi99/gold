<?php $__currentLoopData = ['success' => 'success', 'status' => 'success', 'error' => 'danger', 'warning' => 'warning']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(session($key)): ?>
        <div class="alert alert-<?php echo e($class); ?> alert-dismissible fade show" role="alert">
            <?php echo e(session($key)); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php if($errors->any() && ! request()->routeIs('admin.login', 'admin.password.*')): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
<?php endif; ?>
<?php /**PATH /home/claude/gold/resources/views/admin/partials/flash.blade.php ENDPATH**/ ?>