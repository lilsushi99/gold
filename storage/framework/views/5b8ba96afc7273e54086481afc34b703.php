<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - <?php echo e(config('app.name')); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('cms-assets/vendor/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('cms-assets/admin.css')); ?>">
</head>
<body class="admin">
<?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="main">
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button" onclick="document.querySelector('.sidebar').classList.toggle('open')" aria-label="Toggle menu">&#9776;</button>
            <nav class="crumbs" aria-label="breadcrumb">
                <a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a>
                <?php $__currentLoopData = ($breadcrumbs ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    / <?php if($url): ?> <a href="<?php echo e($url); ?>"><?php echo e($label); ?></a> <?php else: ?> <span><?php echo e($label); ?></span> <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" type="button">
                <?php echo e(auth()->user()->name); ?>

                <span class="text-muted small">(<?php echo e(auth()->user()->role?->name ?? 'No role'); ?>)</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?php echo e(route('admin.password.edit')); ?>">Change password</a></li>
                <li><a class="dropdown-item" href="<?php echo e(url('/')); ?>" target="_blank" rel="noopener">View website</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="post" action="<?php echo e(route('admin.logout')); ?>"><?php echo csrf_field(); ?>
                        <button class="dropdown-item" type="submit">Log out</button>
                    </form>
                </li>
            </ul>
        </div>
    </header>
    <main class="content">
        <?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <h1 class="h4 mb-3"><?php echo $__env->yieldContent('heading', View::getSection('title', 'Dashboard')); ?></h1>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>
<script src="<?php echo e(asset('cms-assets/vendor/bootstrap.min.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/claude/gold/resources/views/layouts/admin.blade.php ENDPATH**/ ?>