@extends('layouts.auth')
@section('title', 'Log in')
@section('content')
    <h1 class="h5 mb-3">Administrator login</h1>
    <form method="post" action="{{ route('admin.login.store') }}" novalidate>@csrf
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="form-check-label"><input type="checkbox" name="remember" class="form-check-input me-1">Remember me</label>
            <a href="{{ route('admin.password.request') }}" class="small">Forgot password?</a>
        </div>
        <button class="btn btn-brand w-100" type="submit">Log in</button>
    </form>
@endsection
