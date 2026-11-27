<?php

namespace App\Services;

use DOMDocument;
use DOMElement;

/** Allow-list SVG sanitiser: strips scripts, event handlers, foreign content and external references. */
class SvgSanitizer
{
    private const ALLOWED_TAGS = ['svg', 'g', 'path', 'circle', 'ellipse', 'line', 'polyline', 'polygon', 'rect', 'defs', 'lineargradient',
        'radialgradient', 'stop', 'clippath', 'mask', 'title', 'desc', 'use', 'text', 'tspan', 'symbol', 'pattern', 'a'];

    /** @return string|null sanitised SVG, or null when the file is not a usable SVG */
    public static function clean(string $svg): ?string
    {
        if (preg_match('/<!ENTITY|<!DOCTYPE[^>]*\[/i', $svg)) {
            return null;                              // XXE / entity expansion
        }
        $prev = libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $ok = $doc->loadXML($svg, LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        if (! $ok || ! $doc->documentElement || strtolower($doc->documentElement->localName) !== 'svg') {
            return null;
        }
        self::walk($doc->documentElement);

        return $doc->saveXML($doc->documentElement);
    }

    private static function walk(DOMElement $el): void
    {
        foreach (iterator_to_array($el->childNodes) as $child) {
            if ($child instanceof DOMElement) {
                if (! in_array(strtolower($child->localName), self::ALLOWED_TAGS, true)) {
                    $el->removeChild($child);
                    continue;
                }
                self::walk($child);
            } elseif ($child->nodeType === XML_COMMENT_NODE || $child->nodeType === XML_PI_NODE) {
                $el->removeChild($child);
            }
        }
        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->nodeName);
            $value = trim($attr->nodeValue);
            $isLink = in_array($name, ['href', 'xlink:href'], true);
            if (str_starts_with($name, 'on')
                || ($isLink && ! str_starts_with($value, '#'))                       // only internal #refs
                || preg_match('/(javascript:|data:|expression\(|url\(\s*[\'"]?\s*(https?:|\/\/|data:|javascript:))/i', $value)) {
                $el->removeAttributeNode($attr);
            }
        }
    }
}
