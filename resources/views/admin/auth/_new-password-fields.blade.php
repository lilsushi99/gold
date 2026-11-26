<div class="mb-3"><label class="form-label" for="password">New password</label>
    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div class="form-text">At least 10 characters with upper and lower case letters and a number.</div></div>
<div class="mb-3"><label class="form-label" for="password_confirmation">Confirm new password</label>
    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password"></div>
