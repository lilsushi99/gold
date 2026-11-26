<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /** group => [key => [default, type]] */
    public const DEFAULTS = [
        'general' => [
            'site_name' => ['Orisa', 'string'], 'site_title' => ['Orisa - Creative Agency', 'string'],
            'tagline' => ['We build brands that last', 'string'], 'site_url' => ['', 'string'],
            'site_description' => ['', 'text'], 'logo' => [null, 'media'], 'logo_dark' => [null, 'media'],
            'favicon' => [null, 'media'], 'default_image' => [null, 'media'],
        ],
        'contact' => [
            'contact_email' => ['', 'string'], 'contact_phone' => ['', 'string'], 'contact_whatsapp' => ['', 'string'],
            'contact_address' => ['', 'text'], 'business_hours' => ['', 'text'],
        ],
        'social' => [
            'social_facebook' => ['', 'string'], 'social_instagram' => ['', 'string'], 'social_linkedin' => ['', 'string'],
            'social_twitter' => ['', 'string'], 'social_youtube' => ['', 'string'], 'social_tiktok' => ['', 'string'],
            'social_behance' => ['', 'string'], 'social_dribbble' => ['', 'string'],
        ],
        'footer' => [
            'footer_description' => ['', 'text'], 'footer_logo' => [null, 'media'], 'footer_copyright' => ['', 'string'],
        ],
        'analytics' => [
            'ga_id' => ['', 'string'], 'gtm_id' => ['', 'string'], 'gsc_verification' => ['', 'string'],
            'bing_verification' => ['', 'string'], 'head_scripts' => ['', 'text'], 'body_scripts' => ['', 'text'],
        ],
        'seo' => [
            'seo_title' => ['', 'string'], 'seo_description' => ['', 'text'], 'seo_keywords' => ['', 'string'],
            'seo_image' => [null, 'media'], 'seo_canonical_base' => ['', 'string'], 'seo_robots_default' => ['index,follow', 'string'],
            'seo_author' => ['', 'string'], 'seo_publisher' => ['', 'string'], 'seo_org_name' => ['', 'string'],
            'seo_org_logo' => [null, 'media'], 'seo_twitter_site' => ['', 'string'], 'seo_og_type' => ['website', 'string'],
            'seo_twitter_card' => ['summary_large_image', 'string'],
            'robots_txt' => ["User-agent: *\nDisallow: /admin\nDisallow: /login\n\nSitemap: {sitemap}\n", 'text'],
            'sitemap_last_generated' => [null, 'string'], 'sitemap_url_count' => ['0', 'int'],
        ],
        'mail' => [
            'mail_notify_to' => ['', 'string'], 'mail_notify_enabled' => ['0', 'bool'],
        ],
    ];

    public function run(): void
    {
        foreach (self::DEFAULTS as $group => $items) {
            foreach ($items as $key => [$value, $type]) {
                Setting::firstOrCreate(['key' => $key], ['group' => $group, 'value' => $value, 'type' => $type]);
            }
        }
    }
}
