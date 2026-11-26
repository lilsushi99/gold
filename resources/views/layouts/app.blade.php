<!doctype html>
<html lang="en" data-bs-theme="{{ setting('default_theme', 'light') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <script>try{var t=localStorage.getItem('theme');if(t==='dark'||t==='light'){document.documentElement.setAttribute('data-bs-theme',t)}}catch(e){}</script>
    @include('partials.seo-head')
    @php $favicon = setting('favicon') ? optional(\App\Models\Media::find(setting('favicon')))->url : null; @endphp
    <link rel="shortcut icon" type="image/x-icon" href="{{ $favicon ?: asset('assets/imgs/template/logo/favicon.svg') }}">
    <!-- Libs CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/carouselTicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/font-awesome-pro.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/spacing.css') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <style>[data-bs-theme=dark] main.bg-neutral-0{background-color:transparent!important}</style>
    @include('partials.analytics-head')
</head>

<body class="at-magic-cursor">
    @include('partials.analytics-body')
    @if ($preview)
        <div style="position:fixed;z-index:99999;left:0;right:0;top:0;background:#c8f169;color:#1d2410;text-align:center;font:600 13px/28px system-ui">PREVIEW - this page is not public ({{ $page->status }})</div>
    @endif
    {!! $globals['global_search'] ?? '' !!}
    {!! $globals['global_header'] ?? '' !!}
    {!! $globals['global_offcanvas'] ?? '' !!}
    <div class="px-blur-bottom"></div>
    <!-- Begin magic cursor -->
    <div id="magic-cursor" class="cursor-black-bg">
        <div id="ball"></div>
    </div>
    <!-- End magic cursor -->
    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main class="bg-neutral-0">
                {!! $body !!}
            </main>
            {!! $globals['global_footer'] ?? '' !!}
        </div>
    </div>
    <!-- back to top start -->
    <div class="back-to-top-wrapper">
        <button id="back_to_top" type="button" class="back-to-top-btn">
            <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 6L6 1L1 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>
    <!-- back to top end -->
    <!-- Libs JS -->
    @foreach (['color-modes', 'jquery-3.7.1.min', 'plugin', 'bootstrap.min', 'swiper-bundle.min', 'jquery.carouselTicker.min', 'jquery.magnific-popup.min', 'jquery.odometer.min', 'jquery.appear', 'jquery.elevatezoom', 'nice-select', 'parallax', 'ripple-2', 'imagesloaded-pkgd', 'isotope.pkgd.min', 'image-hover-effects'] as $js)
        <script src="{{ asset("assets/js/vendors/{$js}.js") }}"></script>
    @endforeach
    <script src="{{ asset('assets/js/at-cursor.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
