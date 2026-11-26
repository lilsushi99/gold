<div class="mb-3"><label class="form-label" for="password">New password</label>
    <input id="password" type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required autocomplete="new-password">
    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <div class="form-text">At least 10 characters with upper and lower case letters and a number.</div></div>
<div class="mb-3"><label class="form-label" for="password_confirmation">Confirm new password</label>
    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password"></div>
<?php /**PATH /home/claude/gold/resources/views/admin/auth/_new-password-fields.blade.php ENDPATH**/ ?>