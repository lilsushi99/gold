@if (setting('gtm_id'))<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ setting('gtm_id') }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>@endif
@if (setting('body_scripts')){!! setting('body_scripts') !!}@endif
