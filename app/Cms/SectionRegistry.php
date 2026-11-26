<?php

namespace App\Cms;

use Illuminate\Support\Facades\File;

/**
 * Registry of section types. Definitions (slot labels/types + the original default values) are generated
 * from the template by tools/cms/build_pages.py and live in resources/cms/sections/*.json.
 */
class SectionRegistry
{
    /** @var array<string, array> */
    private static array $cache = [];

    public static function path(): string
    {
        return resource_path('cms/sections');
    }

    public static function all(): array
    {
        if (! self::$cache) {
            foreach (File::glob(self::path() . '/*.json') as $file) {
                $def = json_decode(File::get($file), true);
                self::$cache[$def['type']] = $def;
            }
            ksort(self::$cache);
        }

        return self::$cache;
    }

    public static function find(string $type): ?array
    {
        return self::all()[$type] ?? null;
    }

    /** Original (default) content for a type: ['slots' => [key => value], 'groups' => [gkey => [items]]]. */
    public static function defaults(string $type): array
    {
        $def = self::find($type) ?? ['slots' => [], 'groups' => []];

        return [
            'slots' => collect($def['slots'])->mapWithKeys(fn ($s) => [$s['key'] => $s['default']])->all(),
            'groups' => collect($def['groups'])->mapWithKeys(fn ($g) => [$g['key'] => $g['items']])->all(),
        ];
    }

    /** Stored content layered over the defaults (a missing slot falls back to the original text). */
    public static function merge(string $type, ?array $content): array
    {
        $d = self::defaults($type);
        $content ??= [];

        return [
            'slots' => array_replace($d['slots'], $content['slots'] ?? []),
            'groups' => array_replace($d['groups'], $content['groups'] ?? []),
        ];
    }

    /** Section types that may be added to a page (everything except global blocks). */
    public static function addable(): array
    {
        return array_filter(self::all(), fn ($d) => $d['category'] !== 'global');
    }
}
