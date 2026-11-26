<?php

if (! function_exists('cms_url')) {
    /**
     * Resolve a stored URL/path for output:
     *  - "assets/..." (template files)  -> /assets/...
     *  - "storage/..." or "/storage/..." (uploaded media) -> as-is root-relative
     *  - absolute URLs, "#", mailto:, tel:, root-relative paths -> unchanged
     */
    function cms_url(?string $value): string
    {
        $v = trim((string) $value);
        if ($v === '') {
            return '';
        }
        if (preg_match('#^(https?:)?//#i', $v) || str_starts_with($v, '#') || preg_match('/^(mailto|tel|sms|whatsapp):/i', $v)) {
            return $v;
        }
        if (str_starts_with($v, '/')) {
            return $v;
        }
        if (str_starts_with($v, 'assets/') || str_starts_with($v, 'storage/')) {
            return asset($v);
        }

        return $v;
    }
}

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}
