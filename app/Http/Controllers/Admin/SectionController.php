<?php

namespace App\Http\Controllers\Admin;

use App\Cms\PageDraft;
use App\Cms\SectionContent;
use App\Cms\SectionRegistry;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    private function locate(Page $page, string $uid): array
    {
        $work = PageDraft::working($page);
        $hit = PageDraft::find($work, $uid);
        abort_unless($hit, 404);

        return [$work, $hit[0], $hit[1]];
    }

    /** Global blocks are fixed: they can be edited but not added/removed/reordered. */
    private function structural(Page $page): void
    {
        abort_if($page->slug === '_global', 403, 'Header and footer blocks are fixed parts of every page.');
    }

    public function store(Request $r, Page $page)
    {
        $this->structural($page);
        $type = $r->validate(['type' => ['required', 'string']])['type'];
        abort_unless(isset(SectionRegistry::addable()[$type]), 422);
        $work = PageDraft::working($page);
        $def = SectionRegistry::find($type);
        $uid = Str::lower(Str::random(8));
        $work['sections'][] = ['uid' => $uid, 'type' => $type, 'label' => $def['label'], 'is_visible' => true, 'content' => SectionRegistry::defaults($type)];
        PageDraft::save($page, $work, $r->user());
        ActivityLogger::log('section.added', $page, "Added section “{$def['label']}” to “{$page->title}”");

        return redirect()->route('admin.sections.edit', [$page, $uid])->with('success', 'Section added with its original content. Edit it below.');
    }

    public function edit(Page $page, string $uid)
    {
        [$work, $i, $section] = $this->locate($page, $uid);
        $def = SectionRegistry::find($section['type']);
        $merged = SectionRegistry::merge($section['type'], $section['content'] ?? null);

        return view('admin.sections.edit', [
            'page' => $page, 'section' => $section, 'def' => $def, 'values' => $merged, 'uid' => $uid,
            'pagePaths' => Page::where('slug', '!=', '_global')->orderBy('title')->get(['title', 'slug', 'is_home']),
            'breadcrumbs' => ['Website Pages' => route('admin.pages.index'), $page->title => route('admin.pages.edit', $page), $section['label'] ?: $def['label'] => null],
        ]);
    }

    public function update(Request $r, Page $page, string $uid)
    {
        [$work, $i, $section] = $this->locate($page, $uid);
        $r->validate(['label' => ['nullable', 'string', 'max:120']]);
        $content = SectionContent::fromRequest($section['type'], ['slots' => $r->input('slots', []), 'groups' => $r->input('groups', [])], $section['content'] ?? []);
        $work['sections'][$i]['content'] = $content;
        if ($r->filled('label') && $page->slug !== '_global') {
            $work['sections'][$i]['label'] = $r->string('label')->toString();
        }
        PageDraft::save($page, $work, $r->user());
        ActivityLogger::log('section.saved', $page, "Edited “" . ($work['sections'][$i]['label'] ?? $section['type']) . "” on “{$page->title}” (draft)");

        $to = $r->input('after') === 'preview' ? route('admin.pages.preview', $page) : route('admin.sections.edit', [$page, $uid]);

        return redirect($to)->with('success', 'Saved to draft. Use “Preview” to check it, then “Publish changes” on the page when you are happy.');
    }

    public function toggle(Request $r, Page $page, string $uid)
    {
        $this->structural($page);
        [$work, $i] = $this->locate($page, $uid);
        $work['sections'][$i]['is_visible'] = ! ($work['sections'][$i]['is_visible'] ?? true);
        PageDraft::save($page, $work, $r->user());

        return back()->with('success', $work['sections'][$i]['is_visible'] ? 'Section will be shown.' : 'Section will be hidden once published.');
    }

    public function move(Request $r, Page $page, string $uid)
    {
        $this->structural($page);
        $dir = $r->validate(['dir' => ['required', 'in:up,down']])['dir'];
        [$work, $i] = $this->locate($page, $uid);
        $j = $dir === 'up' ? $i - 1 : $i + 1;
        if (isset($work['sections'][$j])) {
            [$work['sections'][$i], $work['sections'][$j]] = [$work['sections'][$j], $work['sections'][$i]];
            PageDraft::save($page, $work, $r->user());
        }

        return back();
    }

    public function duplicate(Request $r, Page $page, string $uid)
    {
        $this->structural($page);
        [$work, $i, $section] = $this->locate($page, $uid);
        $copy = $section;
        $copy['uid'] = Str::lower(Str::random(8));
        $copy['label'] = ($section['label'] ?? '') . ' (copy)';
        array_splice($work['sections'], $i + 1, 0, [$copy]);
        PageDraft::save($page, $work, $r->user());

        return back()->with('success', 'Section duplicated.');
    }

    public function destroy(Request $r, Page $page, string $uid)
    {
        $this->structural($page);
        [$work, $i, $section] = $this->locate($page, $uid);
        array_splice($work['sections'], $i, 1);
        PageDraft::save($page, $work, $r->user());
        ActivityLogger::log('section.removed', $page, "Removed section “{$section['label']}” from “{$page->title}” (draft)");

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Section removed from the draft.');
    }
}
