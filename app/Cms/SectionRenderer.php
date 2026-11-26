<?php

namespace App\Cms;

use App\Models\PageSection;

class SectionRenderer
{
    public static function render(PageSection $section): string
    {
        if (! $section->is_visible || ! view()->exists("sections.{$section->type}")) {
            return '';
        }
        $merged = SectionRegistry::merge($section->type, $section->content);

        return view("sections.{$section->type}", ['s' => $merged['slots'], 'g' => $merged['groups']])->render();
    }
}
