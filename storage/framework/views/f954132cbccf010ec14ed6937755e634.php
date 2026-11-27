
<?php $t = $slot['type']; ?>
<?php if($t === 'hidden'): ?>
    <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>" data-attr="<?php echo e($slot['attr'] ?? ''); ?>">
<?php else: ?>
<div class="mb-3">
    <label class="form-label small fw-semibold" for="<?php echo e($fid); ?>"><?php echo e($slot['label']); ?></label>
    <?php if($t === 'image'): ?>
        <div class="cms-image-field">
            <img src="<?php echo e($value ? cms_url($value) : ''); ?>" class="<?php echo e($value ? '' : 'd-none'); ?> mb-2 d-block" alt="">
            <div class="cms-image-empty small text-muted mb-2 <?php echo e($value ? 'd-none' : ''); ?>">No image</div>
            <input type="hidden" id="<?php echo e($fid); ?>" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>" data-value>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-pick>Choose or upload image</button>
            <button type="button" class="btn btn-sm btn-link text-danger" data-clear>Remove</button>
        </div>
    <?php elseif($t === 'textarea'): ?>
        <textarea id="<?php echo e($fid); ?>" class="form-control" name="<?php echo e($name); ?>" rows="4" maxlength="5000"><?php echo e($value); ?></textarea>
    <?php elseif($t === 'url'): ?>
        <input id="<?php echo e($fid); ?>" class="form-control" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>" maxlength="2000" list="page-paths" placeholder="https://… or choose a page">
    <?php elseif($t === 'alt'): ?>
        <input id="<?php echo e($fid); ?>" class="form-control" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>" maxlength="500">
        <div class="form-text">Describes the image for search engines and screen readers.</div>
    <?php else: ?>
        <input id="<?php echo e($fid); ?>" class="form-control" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>" maxlength="5000">
    <?php endif; ?>
    <?php $__errorArgs = [Str::of($name)->replace(['[', ']'], ['.', ''])->toString()];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php endif; ?>
<?php /**PATH /home/claude/gold/resources/views/admin/sections/_field.blade.php ENDPATH**/ ?>