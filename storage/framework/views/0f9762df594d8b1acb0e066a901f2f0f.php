<?php $__env->startSection('title', $section['label'] ?: $def['label']); ?>
<?php $__env->startSection('content'); ?>
<?php $isGlobal = $page->slug === '_global'; ?>
<datalist id="page-paths">
    <?php $__currentLoopData = $pagePaths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($p->is_home ? '/' : '/' . $p->slug); ?>"><?php echo e($p->title); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</datalist>
<form method="post" action="<?php echo e(route('admin.sections.update', [$page, $uid])); ?>" data-loading><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3 sticky-actions">
        <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('admin.pages.edit', $page)); ?>">← Back to page</a>
        <span class="text-muted small me-auto">Changes are saved as a draft - the live site does not change until you publish.</span>
        <button class="btn btn-brand" type="submit">Save draft</button>
        <button class="btn btn-outline-dark" type="submit" name="after" value="preview" formtarget="_blank">Save &amp; preview</button>
    </div>
    <?php if($errors->any()): ?><div class="alert alert-danger"><strong>Please fix the highlighted problems:</strong><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div><?php endif; ?>

    <?php if (! ($isGlobal)): ?>
    <div class="card mb-3"><div class="card-body"><label class="form-label small fw-semibold" for="label">Section name <span class="text-muted fw-normal">(only you see this, to tell sections apart)</span></label>
        <input id="label" class="form-control" name="label" value="<?php echo e(old('label', $section['label'])); ?>" maxlength="120"></div></div>
    <?php endif; ?>

    <?php $plain = collect($def['slots'])->filter(fn ($s) => $s['type'] !== 'hidden'); ?>
    <?php if($plain->isNotEmpty()): ?>
    <div class="card mb-3"><div class="card-header bg-white fw-semibold">Text, images and links</div>
        <div class="card-body"><div class="row">
        <?php $__currentLoopData = $def['slots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="<?php echo e(in_array($slot['type'], ['textarea']) ? 'col-12' : 'col-md-6'); ?>">
                <?php echo $__env->make('admin.sections._field', ['slot' => $slot, 'name' => "slots[{$slot['key']}]", 'value' => old("slots.{$slot['key']}", $values['slots'][$slot['key']] ?? ''), 'fid' => 'f-' . $slot['key']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div></div></div>
    <?php endif; ?>

    <?php $__currentLoopData = $def['groups']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $items = $values['groups'][$g['key']] ?? []; $first = collect($g['slots'])->first(fn ($s) => in_array($s['type'], ['text', 'textarea'])); ?>
        <div class="card mb-3 cms-group" data-min="1">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Repeating items <span class="text-muted fw-normal">(<span class="cms-count"><?php echo e(count($items)); ?></span>)</span></span>
                <button type="button" class="btn btn-sm btn-outline-dark" data-item-action="add">+ Add item</button></div>
            <div class="card-body cms-items d-grid gap-3">
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="cms-item">
                    <div class="cms-item-head d-flex align-items-center gap-2 px-3 py-2">
                        <span class="badge text-bg-dark">#<span class="cms-item-num"><?php echo e($n + 1); ?></span></span>
                        <span class="small text-muted text-truncate me-auto"><?php echo e($first ? \Illuminate\Support\Str::limit($item[$first['key']] ?? '', 60) : ''); ?></span>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary" data-item-action="up" title="Move up">↑</button>
                            <button type="button" class="btn btn-outline-secondary" data-item-action="down" title="Move down">↓</button>
                            <button type="button" class="btn btn-outline-secondary" data-item-action="duplicate">Duplicate</button>
                            <button type="button" class="btn btn-outline-danger" data-item-action="remove">Remove</button></div>
                    </div>
                    <div class="p-3"><div class="row">
                    <?php $__currentLoopData = $g['slots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="<?php echo e($slot['type'] === 'hidden' ? '' : (in_array($slot['type'], ['textarea']) ? 'col-12' : 'col-md-6')); ?>">
                            <?php echo $__env->make('admin.sections._field', ['slot' => $slot, 'name' => "groups[{$g['key']}][{$n}][{$slot['key']}]", 'value' => old("groups.{$g['key']}.{$n}.{$slot['key']}", $item[$slot['key']] ?? ''), 'fid' => "f-{$g['key']}-{$n}-{$slot['key']}"], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="d-flex gap-2 mb-5">
        <button class="btn btn-brand" type="submit">Save draft</button>
        <button class="btn btn-outline-dark" type="submit" name="after" value="preview" formtarget="_blank">Save &amp; preview</button>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/claude/gold/resources/views/admin/sections/edit.blade.php ENDPATH**/ ?>