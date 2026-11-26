<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('cms-assets/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cms-assets/admin.css') }}">
</head>
<body class="admin">
<div class="auth-wrap">
    <div class="auth-card">
        <div class="d-flex align-items-center gap-2 mb-4"><i style="width:30px;height:30px;border-radius:8px;background:var(--brand);display:inline-block"></i><strong class="fs-5">{{ config('app.name') }}</strong></div>
        @include('admin.partials.flash')
        @yield('content')
    </div>
</div>
</body>
</html>
