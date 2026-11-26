<?php $__env->startSection('title', 'Change password'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="h5 mb-2">Change your password</h1>
    <?php if(auth()->user()->must_change_password): ?>
        <p class="text-muted small">For security you must choose a new password before continuing.</p>
    <?php endif; ?>
    <form method="post" action="<?php echo e(route('admin.password.update')); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="mb-3"><label class="form-label" for="current_password">Current password</label>
            <input id="current_password" type="password" name="current_password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required autocomplete="current-password">
            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
        <?php echo $__env->make('admin.auth._new-password-fields', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <button class="btn btn-brand w-100" type="submit">Update password</button>
    </form>
    <form method="post" action="<?php echo e(route('admin.logout')); ?>" class="text-center mt-3"><?php echo csrf_field(); ?>
        <button class="btn btn-link btn-sm" type="submit">Log out</button></form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/claude/gold/resources/views/admin/auth/change-password.blade.php ENDPATH**/ ?>