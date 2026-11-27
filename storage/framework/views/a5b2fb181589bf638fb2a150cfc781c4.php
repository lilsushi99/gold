<?php $__env->startSection('title', 'Edit file'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card"><div class="card-body text-center bg-light" style="min-height:260px;display:grid;place-items:center">
            <?php if($media->isImage()): ?><img src="<?php echo e($media->url); ?>" alt="<?php echo e($media->alt_text); ?>" style="max-width:100%;max-height:480px">
            <?php elseif($media->kind === 'video'): ?><video src="<?php echo e($media->url); ?>" controls style="max-width:100%"></video>
            <?php else: ?><div><span class="badge text-bg-secondary fs-4"><?php echo e(strtoupper($media->extension)); ?></span><div class="mt-2"><a href="<?php echo e($media->url); ?>" target="_blank" rel="noopener">Open file</a></div></div><?php endif; ?>
        </div></div>
        <div class="card mt-3"><div class="card-body">
            <div class="fw-semibold mb-1">File URL</div>
            <div class="input-group"><input class="form-control" readonly id="media-url" value="<?php echo e(url($media->url)); ?>">
                <button class="btn btn-outline-secondary" type="button" data-copy="#media-url">Copy</button></div>
            <dl class="row small mt-3 mb-0">
                <dt class="col-4">File</dt><dd class="col-8"><?php echo e($media->original_name); ?></dd>
                <dt class="col-4">Type</dt><dd class="col-8"><?php echo e($media->mime_type); ?></dd>
                <dt class="col-4">Size</dt><dd class="col-8"><?php echo e(number_format($media->size / 1024, 1)); ?> KB</dd>
                <?php if($media->width): ?><dt class="col-4">Dimensions</dt><dd class="col-8"><?php echo e($media->width); ?> × <?php echo e($media->height); ?> px</dd><?php endif; ?>
                <dt class="col-4">Uploaded</dt><dd class="col-8"><?php echo e($media->created_at->format('d M Y H:i')); ?> <?php if($media->uploader): ?>by <?php echo e($media->uploader->name); ?><?php endif; ?></dd>
            </dl>
        </div></div>
    </div>
    <div class="col-lg-5">
        <form class="card" method="post" action="<?php echo e(route('admin.media.update', $media)); ?>" enctype="multipart/form-data" data-loading><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="card-body">
                <div class="mb-3"><label class="form-label fw-semibold">Alternative text <span class="text-muted fw-normal">(describes the image for search engines and screen readers)</span></label>
                    <input class="form-control" name="alt_text" maxlength="255" value="<?php echo e(old('alt_text', $media->alt_text)); ?>"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Title</label><input class="form-control" name="title" maxlength="255" value="<?php echo e(old('title', $media->title)); ?>"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Description</label><textarea class="form-control" name="description" rows="3" maxlength="2000"><?php echo e(old('description', $media->description)); ?></textarea></div>
                <div class="mb-3"><label class="form-label fw-semibold">Replace file</label><input class="form-control" type="file" name="replacement" accept=".<?php echo e($media->extension); ?>">
                    <div class="form-text">Uploads a new version. The same file type keeps every place it is used working.</div></div>
                <button class="btn btn-brand" type="submit">Save changes</button>
            </div>
        </form>
        <div class="card mt-3"><div class="card-body">
            <div class="fw-semibold mb-2">Where this file is used</div>
            <?php if($usage): ?><ul class="small mb-0"><?php $__currentLoopData = $usage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($u); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            <?php else: ?><p class="small text-muted mb-0">Not used anywhere yet.</p><?php endif; ?>
        </div></div>
        <form class="mt-3" method="post" action="<?php echo e(route('admin.media.destroy', $media)); ?>" data-confirm="Delete <?php echo e($media->original_name); ?>? This cannot be undone."><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="btn btn-outline-danger" type="submit" <?php if($usage): echo 'disabled'; endif; ?>>Delete file</button>
            <?php if($usage): ?><span class="small text-muted ms-2">Remove it from the places above first.</span><?php endif; ?>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/claude/gold/resources/views/admin/media/edit.blade.php ENDPATH**/ ?>