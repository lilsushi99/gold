<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Page;
use App\Models\Setting;

/** Builds the <head> SEO data for a page: page-specific values, falling back to global defaults. */
class SeoService
{
    public static function forPage(Page $page): array
    {
        $seo = $page->seo;
        $siteName = Setting::get('site_name', config('app.name'));
        $base = rtrim((string) (Setting::get('seo_canonical_base') ?: Setting::get('site_url') ?: config('app.url')), '/');

        $title = $seo?->title ?: ($page->is_home ? (Setting::get('seo_title') ?: Setting::get('site_title', $siteName))
            : $page->title . ' - ' . $siteName);
        $description = $seo?->description ?: Setting::get('seo_description') ?: Setting::get('site_description', '');
        $canonical = $seo?->canonical_url ?: $base . $page->path();

        $imageId = $seo?->og_image_id ?: Setting::get('seo_image') ?: Setting::get('default_image');
        $image = $imageId && ($m = Media::find($imageId)) ? url($m->url) : null;

        $index = $seo ? $seo->robots_index : true;
        $follow = $seo ? $seo->robots_follow : true;

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $seo?->keywords ?: Setting::get('seo_keywords'),
            'canonical' => $canonical,
            'robots' => ($index ? 'index' : 'noindex') . ',' . ($follow ? 'follow' : 'nofollow'),
            'og' => [
                'title' => $seo?->og_title ?: $title,
                'description' => $seo?->og_description ?: $description,
                'type' => $seo?->og_type ?: Setting::get('seo_og_type', 'website'),
                'image' => $image,
                'url' => $canonical,
                'site_name' => $siteName,
            ],
            'twitter' => [
                'card' => $seo?->twitter_card ?: Setting::get('seo_twitter_card', 'summary_large_image'),
                'title' => $seo?->twitter_title ?: ($seo?->og_title ?: $title),
                'description' => $seo?->twitter_description ?: ($seo?->og_description ?: $description),
                'image' => $image,
                'site' => Setting::get('seo_twitter_site'),
            ],
            'author' => Setting::get('seo_author'),
            'structured_data' => $seo?->structured_data,
        ];
    }
}
