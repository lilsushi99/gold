<?php $__env->startSection('title', 'New page'); ?>
<?php $__env->startSection('content'); ?>
<form class="card" style="max-width:640px" method="post" action="<?php echo e(route('admin.pages.store')); ?>" data-loading><?php echo csrf_field(); ?>
    <div class="card-body">
        <div class="mb-3"><label class="form-label fw-semibold" for="title">Page title</label>
            <input id="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="title" value="<?php echo e(old('title')); ?>" required maxlength="160" autofocus>
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
        <div class="mb-3"><label class="form-label fw-semibold" for="slug">Web address</label>
            <div class="input-group"><span class="input-group-text"><?php echo e(url('/')); ?>/</span>
                <input id="slug" class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="slug" value="<?php echo e(old('slug')); ?>" required maxlength="160" placeholder="our-story"></div>
            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="form-text">Lowercase letters, numbers and hyphens. Filled in automatically from the title.</div></div>
        <div class="mb-3"><label class="form-label fw-semibold" for="copy_from">Start from</label>
            <select id="copy_from" name="copy_from" class="form-select"><option value="">A blank page (add sections myself)</option>
                <?php $__currentLoopData = $sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s->id); ?>" <?php if(old('copy_from') == $s->id): echo 'selected'; endif; ?>>Copy the sections of “<?php echo e($s->title); ?>”</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
            <div class="form-text">The new page is created as a hidden draft. Nothing is visible to visitors until you publish it.</div></div>
        <button class="btn btn-brand" type="submit">Create page</button> <a class="btn btn-link" href="<?php echo e(route('admin.pages.index')); ?>">Cancel</a>
    </div>
</form>
<?php $__env->startPush('scripts'); ?><script>
(function(){var t=document.getElementById('title'),s=document.getElementById('slug'),touched=false;s.addEventListener('input',function(){touched=true});
t.addEventListener('input',function(){if(!touched)s.value=t.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/[^a-z0-9]+/g,'-').replace(/^-+|-+$/g,'')})})();
</script><?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/claude/gold/resources/views/admin/pages/create.blade.php ENDPATH**/ ?>