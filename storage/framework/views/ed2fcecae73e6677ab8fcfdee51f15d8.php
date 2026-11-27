<?php $__env->startSection('title', $isGlobal ? 'Header, footer & side menu' : $page->title); ?>
<?php $__env->startSection('content'); ?>
<?php
    $pending = $page->hasDraft();
    $featured = ! empty($work['featured_media_id']) ? \App\Models\Media::find($work['featured_media_id']) : null;
?>
<div class="d-flex flex-wrap gap-2 align-items-center mb-3">
    <?php if (! ($isGlobal)): ?>
        <span class="badge text-bg-<?php echo e(['published' => 'success', 'draft' => 'secondary', 'scheduled' => 'info'][$page->status] ?? 'secondary'); ?> fs-6"><?php echo e(['published' => 'Published', 'draft' => 'Hidden from visitors', 'scheduled' => 'Scheduled'][$page->status] ?? $page->status); ?></span>
        <?php if($page->isPublic()): ?><a class="small" href="<?php echo e(url($page->path())); ?>" target="_blank" rel="noopener">View live page ↗</a><?php endif; ?>
    <?php else: ?>
        <span class="text-muted">These blocks appear on every page of the website.</span>
    <?php endif; ?>
    <span class="ms-auto d-flex gap-2">
        <?php if (! ($isGlobal)): ?>
        <form method="post" action="<?php echo e(route('admin.pages.duplicate', $page)); ?>"><?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-secondary">Duplicate page</button></form>
        <?php if (! ($page->is_system)): ?>
        <form method="post" action="<?php echo e(route('admin.pages.destroy', $page)); ?>" data-confirm="Delete “<?php echo e($page->title); ?>” permanently? This cannot be undone."><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-outline-danger">Delete</button></form>
        <?php endif; ?> <?php endif; ?>
    </span>
</div>

<?php if($pending): ?>
<div class="alert alert-warning d-flex flex-wrap gap-3 align-items-center" role="status">
    <div class="me-auto"><strong>You have unpublished changes.</strong>
        <span class="text-muted">Saved <?php echo e($page->draft_updated_at?->diffForHumans()); ?>. Visitors still see the <?php echo e($page->isPublic() ? 'published version' : 'page as hidden'); ?>.</span></div>
    <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('admin.pages.preview', $page)); ?>" target="_blank" rel="noopener">Preview changes</a>
    <form method="post" action="<?php echo e(route('admin.pages.publish', $page)); ?>" class="d-flex gap-2 align-items-center"><?php echo csrf_field(); ?>
        <?php if(! $isGlobal && $page->status === 'draft'): ?><label class="small mb-0"><input type="checkbox" name="go_live" value="1" class="form-check-input" <?php if(! $liveHasSections): echo 'checked'; endif; ?>> Also make the page visible</label><?php endif; ?>
        <button class="btn btn-sm btn-brand" type="submit">Publish changes</button></form>
    <form method="post" action="<?php echo e(route('admin.pages.discard', $page)); ?>" data-confirm="Throw away all unpublished changes to this page?"><?php echo csrf_field(); ?><button class="btn btn-sm btn-link text-danger">Discard changes</button></form>
</div>
<?php else: ?>
<div class="alert alert-light border small mb-3">Everything is published. When you edit a section, your changes are saved as a <strong>draft</strong> first, so the live page is never half-edited.</div>
<?php endif; ?>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-2"><h2 class="h6 mb-0">Sections of this page <span class="text-muted">(<?php echo e(count($work['sections'])); ?>)</span></h2>
            <?php if (! ($isGlobal)): ?>
            <form class="d-flex gap-2" method="post" action="<?php echo e(route('admin.sections.store', $page)); ?>"><?php echo csrf_field(); ?>
                <select class="form-select form-select-sm" name="type" required style="width:260px"><option value="">Add a section…</option>
                    <?php $__currentLoopData = $addable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><optgroup label="<?php echo e($cat === 'home' ? 'Homepage-style sections' : ucfirst($cat)); ?>"><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d['type']); ?>"><?php echo e($d['label']); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></optgroup><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
                <button class="btn btn-sm btn-outline-dark">Add</button></form>
            <?php endif; ?></div>
        <?php $__empty_1 = true; $__currentLoopData = $work['sections']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $d = $defs[$s['type']] ?? null; $vis = $s['is_visible'] ?? true; ?>
            <div class="card section-card mb-2 <?php echo e($vis ? '' : 'is-hidden'); ?>"><div class="card-body py-2 d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted small" style="width:1.6rem"><?php echo e($i + 1); ?></span>
                <div class="me-auto"><a class="fw-semibold text-decoration-none" href="<?php echo e(route('admin.sections.edit', [$page, $s['uid']])); ?>"><?php echo e($s['label'] ?: ($d['label'] ?? $s['type'])); ?></a>
                    <?php if (! ($vis)): ?><span class="badge text-bg-secondary ms-1">Hidden</span><?php endif; ?>
                    <div class="small text-muted"><?php echo e($d ? count($d['slots']) + collect($d['groups'])->sum(fn ($g) => count($g['items'])) . ' editable items' : 'Unknown section type'); ?></div></div>
                <a class="btn btn-sm btn-brand" href="<?php echo e(route('admin.sections.edit', [$page, $s['uid']])); ?>">Edit content</a>
                <?php if (! ($isGlobal)): ?>
                <div class="btn-group btn-group-sm">
                    <form method="post" action="<?php echo e(route('admin.sections.move', [$page, $s['uid']])); ?>"><?php echo csrf_field(); ?><input type="hidden" name="dir" value="up"><button class="btn btn-outline-secondary" title="Move up" <?php if($i === 0): echo 'disabled'; endif; ?>>↑</button></form>
                    <form method="post" action="<?php echo e(route('admin.sections.move', [$page, $s['uid']])); ?>"><?php echo csrf_field(); ?><input type="hidden" name="dir" value="down"><button class="btn btn-outline-secondary" title="Move down" <?php if($i === count($work['sections']) - 1): echo 'disabled'; endif; ?>>↓</button></form>
                </div>
                <form method="post" action="<?php echo e(route('admin.sections.toggle', [$page, $s['uid']])); ?>"><?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-secondary"><?php echo e($vis ? 'Hide' : 'Show'); ?></button></form>
                <form method="post" action="<?php echo e(route('admin.sections.duplicate', [$page, $s['uid']])); ?>"><?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-secondary">Duplicate</button></form>
                <form method="post" action="<?php echo e(route('admin.sections.destroy', [$page, $s['uid']])); ?>" data-confirm="Remove the section “<?php echo e($s['label']); ?>” from this page?"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-outline-danger">Remove</button></form>
                <?php endif; ?>
            </div></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card p-4 text-center text-muted">This page has no sections yet. Use “Add a section” above.</div>
        <?php endif; ?>
    </div>
    <div class="col-lg-4">
        <?php if (! ($isGlobal)): ?>
        <form class="card mb-3" method="post" action="<?php echo e(route('admin.pages.update', $page)); ?>" data-loading><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="card-header bg-white fw-semibold">Page details</div>
            <div class="card-body">
                <div class="mb-3"><label class="form-label small fw-semibold">Page title</label><input class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="title" value="<?php echo e(old('title', $work['title'])); ?>" required maxlength="160"><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                <?php if($page->is_system): ?><div class="mb-3 small text-muted">Address: <code><?php echo e($page->path()); ?></code> (fixed for this page)</div>
                <?php else: ?><div class="mb-3"><label class="form-label small fw-semibold">Web address</label><div class="input-group input-group-sm"><span class="input-group-text">/</span><input class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="slug" value="<?php echo e(old('slug', $work['slug'])); ?>" required></div><?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-text">Changing it after publishing breaks old links - add a redirect afterwards.</div></div><?php endif; ?>
                <div class="mb-3"><label class="form-label small fw-semibold">Short summary</label><textarea class="form-control form-control-sm" name="excerpt" rows="2" maxlength="1000"><?php echo e(old('excerpt', $work['excerpt'] ?? '')); ?></textarea></div>
                <div class="mb-3"><label class="form-label small fw-semibold">Featured image</label>
                    <div class="cms-image-field" data-store-id>
                        <img src="<?php echo e($featured?->url); ?>" class="<?php echo e($featured ? '' : 'd-none'); ?> mb-2" alt="">
                        <div class="cms-image-empty small text-muted mb-2 <?php echo e($featured ? 'd-none' : ''); ?>">No image selected</div>
                        <input type="hidden" name="featured_media_id" value="<?php echo e($work['featured_media_id'] ?? ''); ?>" data-value>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-pick>Choose image</button>
                        <button type="button" class="btn btn-sm btn-link text-danger" data-clear>Remove</button></div></div>
                <button class="btn btn-sm btn-brand" type="submit">Save to draft</button>
            </div>
        </form>
        <form class="card mb-3" method="post" action="<?php echo e(route('admin.pages.visibility', $page)); ?>" data-loading><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="card-header bg-white fw-semibold">Visibility</div>
            <div class="card-body">
                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="alert alert-danger py-2 small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php $__currentLoopData = ['published' => 'Published - visible to everyone', 'draft' => 'Hidden - only admins can preview', 'scheduled' => 'Scheduled - goes live at a set time']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check"><input class="form-check-input" type="radio" name="status" id="st-<?php echo e($v); ?>" value="<?php echo e($v); ?>" <?php if(old('status', $page->status) === $v): echo 'checked'; endif; ?> <?php if($page->is_home && $v !== 'published'): echo 'disabled'; endif; ?>><label class="form-check-label small" for="st-<?php echo e($v); ?>"><?php echo e($l); ?></label></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <input type="datetime-local" class="form-control form-control-sm mt-2 <?php $__errorArgs = ['publish_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="publish_at" value="<?php echo e(old('publish_at', $page->publish_at?->format('Y-m-d\TH:i'))); ?>"><?php $__errorArgs = ['publish_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <button class="btn btn-sm btn-outline-dark mt-3" type="submit">Update visibility</button>
            </div>
        </form>
        <?php endif; ?>
        <div class="card"><div class="card-header bg-white fw-semibold">Earlier versions</div>
            <ul class="list-group list-group-flush small">
                <?php $__empty_1 = true; $__currentLoopData = $revisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center"><span><?php echo e($rev->created_at?->format('d M Y H:i')); ?><br><span class="text-muted"><?php echo e($rev->author?->name ?? 'System'); ?></span></span>
                        <form method="post" action="<?php echo e(route('admin.pages.restore', [$page, $rev])); ?>" data-confirm="Load this earlier version into your draft? Your current unpublished changes will be replaced."><?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-secondary">Restore</button></form></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><li class="list-group-item text-muted">A copy of the live page is kept here every time you publish.</li><?php endif; ?>
            </ul></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/claude/gold/resources/views/admin/pages/edit.blade.php ENDPATH**/ ?>