<?php

namespace App\Cms;

use App\Models\PageSection;

class SectionRenderer
{
    public static function render(PageSection $section): string
    {
        return self::renderData($section->type, $section->content, (bool) $section->is_visible);
    }

    /** Render from raw data (used for live sections and for unpublished drafts in preview). */
    public static function renderData(string $type, ?array $content, bool $visible = true): string
    {
        if (! $visible || ! view()->exists("sections.{$type}")) {
            return '';
        }
        $merged = SectionRegistry::merge($type, $content);

        return view("sections.{$type}", ['s' => $merged['slots'], 'g' => $merged['groups']])->render();
    }
}
