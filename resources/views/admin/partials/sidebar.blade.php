@php
    $u = auth()->user();
    // [label, route name, permission, svg path]
    $groups = [
        'Overview' => [['Dashboard', 'admin.dashboard', 'dashboard.view', 'M3 12l9-9 9 9M5 10v10h14V10']],
        'Content' => [
            ['Website Pages', 'admin.pages.index', 'pages.manage', 'M6 2h9l5 5v15H6z'],
            ['Blog / News', 'admin.posts.index', 'posts.manage', 'M4 5h16M4 10h16M4 15h10'],
            ['Media Library', 'admin.media.index', 'media.manage', 'M3 5h18v14H3zM3 15l5-5 4 4 3-3 6 6'],
            ['Navigation', 'admin.menus.index', 'menus.manage', 'M4 6h16M4 12h16M4 18h16'],
            ['Content Modules', 'admin.modules.index', 'content.manage', 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z'],
            ['Enquiries', 'admin.submissions.index', 'submissions.manage', 'M3 6h18v12H3zM3 7l9 6 9-6'],
        ],
        'SEO' => [
            ['SEO Manager', 'admin.seo.index', 'seo.manage', 'M11 4a7 7 0 100 14 7 7 0 000-14zM21 21l-5-5'],
            ['Redirects', 'admin.redirects.index', 'redirects.manage', 'M5 12h14M13 6l6 6-6 6'],
        ],
        'System' => [
            ['Website Settings', 'admin.settings.index', 'settings.manage', 'M12 8a4 4 0 100 8 4 4 0 000-8z'],
            ['Users & Roles', 'admin.users.index', 'users.manage', 'M16 11a4 4 0 10-8 0M4 21a8 8 0 0116 0'],
            ['Activity Logs', 'admin.logs.index', 'logs.view', 'M4 4h16v16H4zM8 9h8M8 13h8'],
        ],
    ];
@endphp
<aside class="sidebar">
    <a class="brand" href="{{ route('admin.dashboard') }}"><i></i> {{ config('app.name') }}</a>
    @foreach ($groups as $group => $items)
        @php $visible = collect($items)->filter(fn ($i) => $u->hasPermission($i[2]) && \Illuminate\Support\Facades\Route::has($i[1])); @endphp
        @if ($visible->isNotEmpty())
            <div class="grp">{{ $group }}</div>
            @foreach ($visible as [$label, $route, $perm, $svg])
                <a class="nav-i {{ request()->routeIs(Str::beforeLast($route, '.') . '*') ? 'active' : '' }}" href="{{ route($route) }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $svg }}"/></svg>
                    {{ $label }}
                </a>
            @endforeach
        @endif
    @endforeach
</aside>
