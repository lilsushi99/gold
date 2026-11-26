@extends('layouts.auth')
@section('title', 'Change password')
@section('content')
    <h1 class="h5 mb-2">Change your password</h1>
    @if (auth()->user()->must_change_password)
        <p class="text-muted small">For security you must choose a new password before continuing.</p>
    @endif
    <form method="post" action="{{ route('admin.password.update') }}">@csrf @method('PUT')
        <div class="mb-3"><label class="form-label" for="current_password">Current password</label>
            <input id="current_password" type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required autocomplete="current-password">
            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        @include('admin.auth._new-password-fields')
        <button class="btn btn-brand w-100" type="submit">Update password</button>
    </form>
    <form method="post" action="{{ route('admin.logout') }}" class="text-center mt-3">@csrf
        <button class="btn btn-link btn-sm" type="submit">Log out</button></form>
@endsection
