<?php $__env->startSection('title', 'Media Library'); ?>
<?php $__env->startSection('content'); ?>
<div class="card mb-3"><div class="card-body">
    <form method="post" action="<?php echo e(route('admin.media.store')); ?>" enctype="multipart/form-data" class="row g-2 align-items-end" data-loading><?php echo csrf_field(); ?>
        <div class="col-md">
            <label class="form-label fw-semibold" for="files">Upload files</label>
            <input id="files" class="form-control" type="file" name="files[]" multiple accept="<?php echo e($accept); ?>" required>
            <div class="form-text">Images (JPG, PNG, WebP, GIF, SVG), PDF, Office documents and MP4/WebM video. Up to <?php echo e($maxMb); ?> MB each; very large photos are scaled down automatically.</div>
        </div>
        <div class="col-md-auto"><button class="btn btn-brand" type="submit">Upload</button></div>
    </form>
</div></div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-5"><input class="form-control" name="q" value="<?php echo e(request('q')); ?>" placeholder="Search by name, title or alt text…"></div>
    <div class="col-md-3"><select class="form-select" name="kind" onchange="this.form.submit()">
        <option value="">All file types</option>
        <?php $__currentLoopData = ['image' => 'Images', 'document' => 'Documents', 'video' => 'Videos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if(request('kind') === $k): echo 'selected'; endif; ?>><?php echo e($l); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select></div>
    <div class="col-auto"><button class="btn btn-outline-secondary">Search</button> <?php if(request()->hasAny(['q','kind'])): ?><a class="btn btn-link" href="<?php echo e(route('admin.media.index')); ?>">Clear</a><?php endif; ?></div>
</form>

<div class="row g-3">
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a class="card h-100 text-decoration-none text-reset media-tile" href="<?php echo e(route('admin.media.edit', $m)); ?>">
                <div class="media-thumb">
                    <?php if($m->isImage()): ?><img src="<?php echo e($m->url); ?>" alt="<?php echo e($m->alt_text); ?>" loading="lazy">
                    <?php else: ?><span class="badge text-bg-secondary fs-6"><?php echo e(strtoupper($m->extension)); ?></span><?php endif; ?>
                </div>
                <div class="card-body p-2"><div class="small text-truncate fw-semibold" title="<?php echo e($m->original_name); ?>"><?php echo e($m->original_name); ?></div>
                    <div class="small text-muted"><?php echo e(number_format($m->size / 1024)); ?> KB <?php if($m->width): ?>· <?php echo e($m->width); ?>×<?php echo e($m->height); ?><?php endif; ?></div>
                    <?php if($m->isImage() && ! $m->alt_text): ?><span class="badge text-bg-warning">No alt text</span><?php endif; ?></div>
            </a>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12"><div class="card p-4 text-center text-muted">No files found. Upload your first image above.</div></div>
    <?php endif; ?>
</div>
<div class="mt-3"><?php echo e($items->links('pagination::bootstrap-5')); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/claude/gold/resources/views/admin/media/index.blade.php ENDPATH**/ ?>