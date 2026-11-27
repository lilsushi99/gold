<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\DB;

/** Finds where a media file is referenced so it is never deleted while still in use. */
class MediaUsage
{
    /** @return array<int,string> human readable places */
    public static function for(Media $m): array
    {
        $needle = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $m->path);   // LIKE-safe
        $json = str_replace('/', '\\\\/', $needle);                                  // JSON escapes "/" as "\/"
        $found = [];

        $sections = DB::table('page_sections')->join('pages', 'pages.id', '=', 'page_sections.page_id')
            ->where(fn ($q) => $q->where('page_sections.content', 'like', "%{$needle}%")->orWhere('page_sections.content', 'like', "%{$json}%"))
            ->get(['pages.title', 'page_sections.label']);
        foreach ($sections as $r) {
            $found[] = "Page “{$r->title}” → {$r->label}";
        }
        $drafts = DB::table('pages')->whereNotNull('draft')
            ->where(fn ($q) => $q->where('draft', 'like', "%{$needle}%")->orWhere('draft', 'like', "%{$json}%"))->pluck('title');
        foreach ($drafts as $t) {
            $found[] = "Unpublished draft of “{$t}”";
        }
        foreach (DB::table('pages')->where('featured_media_id', $m->id)->pluck('title') as $t) {
            $found[] = "Featured image of page “{$t}”";
        }
        foreach (DB::table('settings')->where('type', 'media')->where('value', (string) $m->id)->pluck('key') as $k) {
            $found[] = "Website setting “{$k}”";
        }
        foreach (['posts' => ['featured_media_id', 'Blog post'], 'services' => ['media_id', 'Service'], 'projects' => ['cover_media_id', 'Project'],
                  'team_members' => ['media_id', 'Team member'], 'testimonials' => ['media_id', 'Testimonial']] as $table => [$col, $label]) {
            if (DB::table($table)->where($col, $m->id)->exists()) {
                $found[] = "{$label} image";
            }
        }
        if (DB::table('seo_metadata')->where('og_image_id', $m->id)->orWhere('twitter_image_id', $m->id)->exists()) {
            $found[] = 'SEO social sharing image';
        }

        return array_values(array_unique($found));
    }
}
