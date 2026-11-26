<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('content'); ?>
    <div class="row g-3 mb-4">
        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-lg"><div class="card p-3"><div class="stat"><?php echo e($value); ?></div><div class="text-muted small mt-1"><?php echo e($label); ?></div></div></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="card">
        <div class="card-header bg-white fw-semibold">Recent activity</div>
        <ul class="list-group list-group-flush">
            <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="list-group-item d-flex justify-content-between">
                    <span><?php echo e($log->description ?? $log->action); ?> <span class="text-muted">- <?php echo e($log->user?->name ?? 'System'); ?></span></span>
                    <span class="text-muted small"><?php echo e($log->created_at?->diffForHumans()); ?></span>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="list-group-item text-muted">No activity yet.</li>
            <?php endif; ?>
        </ul>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/claude/gold/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>