<?php $__env->startSection('title', 'Website Pages'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
    <form method="get" class="d-flex gap-2 flex-wrap">
        <input class="form-control" style="width:260px" name="q" value="<?php echo e(request('q')); ?>" placeholder="Search pages…">
        <select class="form-select" style="width:210px" name="status" onchange="this.form.submit()">
            <option value="">All pages</option>
            <?php $__currentLoopData = ['published' => 'Published', 'draft' => 'Hidden / not published', 'scheduled' => 'Scheduled', 'pending' => 'With unpublished changes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if(request('status') === $k): echo 'selected'; endif; ?>><?php echo e($l); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="btn btn-outline-secondary">Search</button>
    </form>
    <a class="btn btn-brand" href="<?php echo e(route('admin.pages.create')); ?>">+ New page</a>
</div>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
    <thead class="table-light"><tr><th>Page</th><th>Address</th><th>Status</th><th>Last updated</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><a class="fw-semibold text-decoration-none" href="<?php echo e(route('admin.pages.edit', $p)); ?>"><?php echo e($p->slug === '_global' ? 'Header, footer & side menu' : $p->title); ?></a>
                <?php if($p->is_home): ?><span class="badge text-bg-dark ms-1">Homepage</span><?php endif; ?>
                <?php if($p->slug === '_global'): ?><span class="badge text-bg-secondary ms-1">Shown on every page</span><?php endif; ?>
                <?php if($p->hasDraft()): ?><span class="badge badge-dev ms-1">Unpublished changes</span><?php endif; ?></td>
            <td class="text-muted small"><?php echo e($p->slug === '_global' ? '-' : $p->path()); ?></td>
            <td><?php if($p->slug === '_global'): ?><span class="text-muted">-</span>
                <?php else: ?><span class="badge text-bg-<?php echo e(['published' => 'success', 'draft' => 'secondary', 'scheduled' => 'info'][$p->status] ?? 'secondary'); ?>"><?php echo e(['published' => 'Published', 'draft' => 'Hidden', 'scheduled' => 'Scheduled'][$p->status] ?? $p->status); ?></span>
                <?php if($p->status === 'scheduled'): ?><div class="small text-muted"><?php echo e($p->publish_at?->format('d M Y H:i')); ?></div><?php endif; ?> <?php endif; ?></td>
            <td class="small text-muted"><?php echo e(($p->draft_updated_at ?? $p->updated_at)?->diffForHumans()); ?></td>
            <td class="text-end text-nowrap">
                <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('admin.pages.preview', $p)); ?>" target="_blank" rel="noopener">Preview</a>
                <a class="btn btn-sm btn-brand" href="<?php echo e(route('admin.pages.edit', $p)); ?>">Edit</a>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="5" class="text-center text-muted py-4">No pages match.</td></tr>
    <?php endif; ?>
    </tbody></table></div></div>
<div class="mt-3"><?php echo e($pages->links('pagination::bootstrap-5')); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/claude/gold/resources/views/admin/pages/index.blade.php ENDPATH**/ ?>