<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?php echo $__env->yieldContent('title'); ?> - <?php echo e(config('app.name')); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('cms-assets/vendor/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('cms-assets/admin.css')); ?>">
</head>
<body class="admin">
<div class="auth-wrap">
    <div class="auth-card">
        <div class="d-flex align-items-center gap-2 mb-4"><i style="width:30px;height:30px;border-radius:8px;background:var(--brand);display:inline-block"></i><strong class="fs-5"><?php echo e(config('app.name')); ?></strong></div>
        <?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>
</body>
</html>
<?php /**PATH /home/claude/gold/resources/views/layouts/auth.blade.php ENDPATH**/ ?>