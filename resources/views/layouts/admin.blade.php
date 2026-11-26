<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('cms-assets/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cms-assets/admin.css') }}">
</head>
<body class="admin">
@include('admin.partials.sidebar')
<div class="main">
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button" onclick="document.querySelector('.sidebar').classList.toggle('open')" aria-label="Toggle menu">&#9776;</button>
            <nav class="crumbs" aria-label="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Admin</a>
                @foreach (($breadcrumbs ?? []) as $label => $url)
                    / @if ($url) <a href="{{ $url }}">{{ $label }}</a> @else <span>{{ $label }}</span> @endif
                @endforeach
            </nav>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" type="button">
                {{ auth()->user()->name }}
                <span class="text-muted small">({{ auth()->user()->role?->name ?? 'No role' }})</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('admin.password.edit') }}">Change password</a></li>
                <li><a class="dropdown-item" href="{{ url('/') }}" target="_blank" rel="noopener">View website</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="post" action="{{ route('admin.logout') }}">@csrf
                        <button class="dropdown-item" type="submit">Log out</button>
                    </form>
                </li>
            </ul>
        </div>
    </header>
    <main class="content">
        @include('admin.partials.flash')
        <h1 class="h4 mb-3">@yield('heading', View::getSection('title', 'Dashboard'))</h1>
        @yield('content')
    </main>
</div>
<script src="{{ asset('cms-assets/vendor/bootstrap.min.js') }}"></script>
@stack('scripts')
</body>
</html>
