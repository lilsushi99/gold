@extends('layouts.auth')
@section('title', 'Choose a new password')
@section('content')
    <h1 class="h5 mb-3">Choose a new password</h1>
    <form method="post" action="{{ route('admin.password.update.guest') }}">@csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-3"><label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        @include('admin.auth._new-password-fields')
        <button class="btn btn-brand w-100" type="submit">Reset password</button>
    </form>
@endsection
