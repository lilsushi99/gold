<?php

namespace App\Cms;

use Illuminate\Validation\ValidationException;

/** Validates and normalises submitted section form data against the section definition. */
class SectionContent
{
    public const MAX_ITEMS = 100;

    public static function clean(string $str, int $max = 5000): string
    {
        $str = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', (string) $str) ?? '';

        return mb_substr(str_replace("\r\n", "\n", $str), 0, $max);
    }

    /** @return string|null error message when the URL is not acceptable */
    public static function urlError(string $url): ?string
    {
        $probe = preg_replace('/[\x00-\x20\x7F]+/', '', $url);
        if (preg_match('/^(javascript|data|vbscript|file):/i', $probe)) {
            return 'This kind of link is not allowed.';
        }
        if (preg_match('/^[a-z][a-z0-9+.\-]*:/i', $probe) && ! preg_match('/^(https?|mailto|tel|sms|whatsapp):/i', $probe)) {
            return 'Use a normal web address (https://…), an email (mailto:…), a phone (tel:…) or a page address (/about).';
        }

        return null;
    }

    /** Hidden svg values must already be known template/previous values (never free-form markup). */
    private static function allowedHidden(string $type, string $gkey, string $skey, array $existing): array
    {
        $allowed = [];
        $def = SectionRegistry::find($type);
        foreach ($def['groups'] ?? [] as $g) {
            if ($g['key'] === $gkey) {
                foreach ($g['items'] as $item) {
                    $allowed[] = $item[$skey] ?? null;
                }
            }
        }
        foreach ($existing['groups'][$gkey] ?? [] as $item) {
            $allowed[] = $item[$skey] ?? null;
        }

        return array_filter($allowed, fn ($v) => $v !== null);
    }

    /**
     * @param  array $input    ['slots' => [...], 'groups' => [gkey => [ [slot=>val], ... ]]]
     * @param  array $existing current stored content ({slots, groups}) used for untouched keys
     */
    public static function fromRequest(string $type, array $input, array $existing): array
    {
        $def = SectionRegistry::find($type) ?? throw new \InvalidArgumentException("Unknown section type {$type}");
        $merged = SectionRegistry::merge($type, $existing);
        $errors = [];
        $slots = $merged['slots'];
        $groups = $merged['groups'];

        foreach ($def['slots'] as $slot) {
            $k = $slot['key'];
            if (! array_key_exists($k, $input['slots'] ?? [])) {
                continue;
            }
            $v = is_string($input['slots'][$k]) ? $input['slots'][$k] : '';
            $v = self::clean($v, $slot['type'] === 'hidden' ? 20000 : 5000);
            if (in_array($slot['type'], ['url', 'image'], true) && $v !== '' && ($e = self::urlError($v))) {
                $errors["slots.{$k}"] = "{$slot['label']}: {$e}";
            }
            if ($slot['type'] === 'hidden') {
                continue;                                     // top-level hidden slots do not exist; ignore
            }
            $slots[$k] = $v;
        }

        foreach ($def['groups'] as $g) {
            $gk = $g['key'];
            if (! isset($input['groups'][$gk]) || ! is_array($input['groups'][$gk])) {
                continue;
            }
            $items = array_values($input['groups'][$gk]);
            if (count($items) > self::MAX_ITEMS) {
                $errors["groups.{$gk}"] = 'Too many items (maximum ' . self::MAX_ITEMS . ').';
                continue;
            }
            $out = [];
            foreach ($items as $n => $item) {
                $row = [];
                foreach ($g['slots'] as $slot) {
                    $k = $slot['key'];
                    $v = is_array($item) && is_string($item[$k] ?? null) ? $item[$k] : '';
                    if ($slot['type'] === 'hidden') {
                        $v = ($slot['attr'] ?? '') === '__svg__'
                            ? (in_array($v, self::allowedHidden($type, $gk, $k, $existing), true) ? $v : '')
                            : self::clean($v, 500);
                    } else {
                        $v = self::clean($v);
                        if (in_array($slot['type'], ['url', 'image'], true) && $v !== '' && ($e = self::urlError($v))) {
                            $errors["groups.{$gk}.{$n}.{$k}"] = "Item " . ($n + 1) . " – {$slot['label']}: {$e}";
                        }
                    }
                    $row[$k] = $v;
                }
                $out[] = $row;
            }
            $groups[$gk] = $out;
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        return ['slots' => $slots, 'groups' => $groups];
    }
}
