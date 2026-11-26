<title>{{ $seo['title'] }}</title>
@if ($seo['description'])<meta name="description" content="{{ $seo['description'] }}">@endif
@if ($seo['keywords'])<meta name="keywords" content="{{ $seo['keywords'] }}">@endif
@if ($seo['author'])<meta name="author" content="{{ $seo['author'] }}">@endif
<meta name="robots" content="{{ $preview ?? false ? 'noindex,nofollow' : $seo['robots'] }}">
<link rel="canonical" href="{{ $seo['canonical'] }}">
<meta property="og:title" content="{{ $seo['og']['title'] }}">
@if ($seo['og']['description'])<meta property="og:description" content="{{ $seo['og']['description'] }}">@endif
<meta property="og:type" content="{{ $seo['og']['type'] }}">
<meta property="og:url" content="{{ $seo['og']['url'] }}">
<meta property="og:site_name" content="{{ $seo['og']['site_name'] }}">
@if ($seo['og']['image'])<meta property="og:image" content="{{ $seo['og']['image'] }}">@endif
<meta name="twitter:card" content="{{ $seo['twitter']['card'] }}">
<meta name="twitter:title" content="{{ $seo['twitter']['title'] }}">
@if ($seo['twitter']['description'])<meta name="twitter:description" content="{{ $seo['twitter']['description'] }}">@endif
@if ($seo['twitter']['image'])<meta name="twitter:image" content="{{ $seo['twitter']['image'] }}">@endif
@if ($seo['twitter']['site'])<meta name="twitter:site" content="{{ $seo['twitter']['site'] }}">@endif
@if (setting('gsc_verification'))<meta name="google-site-verification" content="{{ setting('gsc_verification') }}">@endif
@if (setting('bing_verification'))<meta name="msvalidate.01" content="{{ setting('bing_verification') }}">@endif
