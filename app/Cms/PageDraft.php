<?php

namespace App\Cms;

use App\Models\Page;
use App\Models\PageRevision;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Draft / publish workflow. Editors only ever change `pages.draft` (a JSON copy of the page + its sections);
 * the live `pages` / `page_sections` rows - what visitors see - change only when the draft is published.
 */
class PageDraft
{
    public const RESERVED_SLUGS = ['admin', 'storage', 'assets', 'cms-assets', 'up', 'login', 'logout', 'sitemap.xml', 'robots.txt', 'home', '_global', 'vendor', 'build'];
    public const MAX_REVISIONS = 25;

    /** The live state as a draft-shaped array. */
    public static function snapshot(Page $page): array
    {
        return [
            'title' => $page->title, 'slug' => $page->slug, 'excerpt' => $page->excerpt, 'featured_media_id' => $page->featured_media_id,
            'sections' => $page->sections()->orderBy('position')->get()->map(fn ($s) => [
                'uid' => 's' . $s->id, 'type' => $s->type, 'label' => $s->label,   // stable until a draft exists
                
                'is_visible' => (bool) $s->is_visible, 'content' => $s->content ?? SectionRegistry::defaults($s->type),
            ])->all(),
        ];
    }

    /** Working copy for editing: the saved draft, or a fresh copy of the live page (not persisted until saved). */
    public static function working(Page $page): array
    {
        return $page->hasDraft() ? $page->draft : self::snapshot($page);
    }

    public static function save(Page $page, array $data, ?User $user = null): void
    {
        $data['sections'] = array_values($data['sections'] ?? []);
        $page->forceFill(['draft' => $data, 'draft_updated_at' => now(), 'draft_updated_by' => $user?->id])->save();
    }

    public static function discard(Page $page): void
    {
        $page->forceFill(['draft' => null, 'draft_updated_at' => null, 'draft_updated_by' => null])->save();
    }

    public static function validateSlug(string $slug, ?Page $page = null): string
    {
        $slug = trim(Str::lower($slug), '/ ');
        $ok = preg_match('#^[a-z0-9]+(?:-[a-z0-9]+)*(?:/[a-z0-9]+(?:-[a-z0-9]+)*)*$#', $slug)
            && ! in_array(explode('/', $slug)[0], self::RESERVED_SLUGS, true);
        if (! $ok) {
            throw ValidationException::withMessages(['slug' => 'Use lowercase letters, numbers and hyphens only (e.g. "our-story"). This address is reserved or invalid.']);
        }
        if (Page::where('slug', $slug)->when($page, fn ($q) => $q->where('id', '!=', $page->id))->exists()) {
            throw ValidationException::withMessages(['slug' => 'Another page already uses this address.']);
        }

        return $slug;
    }

    /** Apply the draft to the live page (atomic). The previous live version is kept as a revision. */
    public static function publish(Page $page, User $user): void
    {
        $d = $page->draft;
        if (! $d) {
            return;
        }
        DB::transaction(function () use ($page, $user, $d) {
            $slug = $page->is_system ? $page->slug : self::validateSlug($d['slug'] ?? $page->slug, $page);
            PageRevision::create(['page_id' => $page->id, 'snapshot' => self::snapshot($page), 'note' => 'Before publishing changes',
                'created_by' => $user->id, 'created_at' => now()]);
            $page->forceFill([
                'title' => $d['title'] ?: $page->title, 'slug' => $slug, 'excerpt' => $d['excerpt'] ?? null,
                'featured_media_id' => $d['featured_media_id'] ?? null, 'updated_by' => $user->id,
                'draft' => null, 'draft_updated_at' => null, 'draft_updated_by' => null,
            ])->save();
            // Update live rows in place where possible so section addresses ("s<id>") stay stable across publishes.
            $existing = $page->sections()->get()->keyBy('id');
            $keep = [];
            foreach (array_values($d['sections'] ?? []) as $i => $s) {
                $attrs = ['type' => $s['type'], 'label' => $s['label'] ?? null, 'position' => $i,
                    'is_visible' => (bool) ($s['is_visible'] ?? true), 'content' => $s['content'] ?? null];
                $id = preg_match('/^s(\d+)$/', $s['uid'] ?? '', $m) ? (int) $m[1] : null;
                if ($id && $existing->has($id) && ! in_array($id, $keep, true)) {
                    $existing[$id]->update($attrs);
                    $keep[] = $id;
                } else {
                    $keep[] = $page->sections()->create($attrs)->id;
                }
            }
            $page->sections()->whereNotIn('id', $keep)->delete();
            // (MySQL cannot OFFSET without LIMIT, so slice in PHP; revisions per page are few)
            $old = $page->revisions()->orderByDesc('id')->pluck('id')->slice(self::MAX_REVISIONS);
            if ($old->isNotEmpty()) {
                PageRevision::whereIn('id', $old)->delete();
            }
        });
    }

    public static function restoreAsDraft(Page $page, PageRevision $rev, User $user): void
    {
        // Keep the snapshot's section addresses so restored sections map back onto their live rows on publish.
        self::save($page, $rev->snapshot, $user);
    }

    /** Find a section in a working copy by uid. @return array{0:int,1:array}|null */
    public static function find(array $data, string $uid): ?array
    {
        foreach ($data['sections'] as $i => $s) {
            if ($s['uid'] === $uid) {
                return [$i, $s];
            }
        }

        return null;
    }
}
