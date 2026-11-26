@extends('layouts.auth')
@section('title', 'Forgot password')
@section('content')
    <h1 class="h5 mb-3">Reset your password</h1>
    <form method="post" action="{{ route('admin.password.email') }}">@csrf
        <div class="mb-3"><label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus></div>
        <button class="btn btn-brand w-100" type="submit">Email reset link</button>
        <a class="d-block text-center mt-3 small" href="{{ route('admin.login') }}">Back to login</a>
    </form>
@endsection
