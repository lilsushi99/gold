<?php

namespace App\Http\Controllers\Admin;

use App\Cms\PageDraft;
use App\Cms\SectionRegistry;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageRevision;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PageController extends Controller
{
    private function guardGlobal(Page $page): void
    {
        abort_if($page->slug === '_global' && false, 404);
    }

    public function index(Request $r)
    {
        $pages = Page::query()
            ->when($r->filled('q'), function ($q) use ($r) {
                $t = '%' . addcslashes($r->string('q'), '%_\\') . '%';
                $q->where(fn ($q) => $q->where('title', 'like', $t)->orWhere('slug', 'like', $t));
            })
            ->when($r->string('status')->toString() === 'pending', fn ($q) => $q->whereNotNull('draft'))
            ->when(in_array($r->string('status')->toString(), ['published', 'draft', 'scheduled'], true), fn ($q) => $q->where('status', $r->string('status')))
            ->orderByRaw("slug = '_global'")->orderByDesc('is_home')->orderBy('position')->orderBy('title')
            ->paginate(15)->withQueryString();

        return view('admin.pages.index', ['pages' => $pages, 'breadcrumbs' => ['Website Pages' => null]]);
    }

    public function create()
    {
        return view('admin.pages.create', [
            'sources' => Page::where('slug', '!=', '_global')->orderBy('title')->get(['id', 'title']),
            'breadcrumbs' => ['Website Pages' => route('admin.pages.index'), 'New page' => null],
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate(['title' => ['required', 'string', 'max:160'], 'slug' => ['required', 'string', 'max:160'], 'copy_from' => ['nullable', 'integer', 'exists:pages,id']]);
        $slug = PageDraft::validateSlug($data['slug']);
        $page = Page::create(['title' => $data['title'], 'slug' => $slug, 'template' => 'default', 'status' => 'draft',
            'position' => (Page::max('position') ?? 0) + 1, 'created_by' => $r->user()->id, 'updated_by' => $r->user()->id]);
        $sections = [];
        if (! empty($data['copy_from'])) {
            $src = Page::find($data['copy_from']);
            $sections = PageDraft::working($src)['sections'];
            foreach ($sections as &$s) {
                $s['uid'] = Str::lower(Str::random(8));
            }
        }
        PageDraft::save($page, ['title' => $page->title, 'slug' => $slug, 'excerpt' => null, 'featured_media_id' => null, 'sections' => $sections], $r->user());
        ActivityLogger::log('page.created', $page, "Created page “{$page->title}”");

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Page created as a draft. Add sections, then publish when you are ready.');
    }

    public function edit(Page $page)
    {
        $work = PageDraft::working($page);
        $defs = SectionRegistry::all();

        return view('admin.pages.edit', [
            'page' => $page, 'work' => $work, 'defs' => $defs, 'isGlobal' => $page->slug === '_global',
            'addable' => collect(SectionRegistry::addable())->groupBy('category'),
            'revisions' => $page->revisions()->with('author')->orderByDesc('id')->limit(10)->get(),
            'liveHasSections' => $page->sections()->exists(),
            'breadcrumbs' => ['Website Pages' => route('admin.pages.index'), $page->title => null],
        ]);
    }

    /** Title / address / summary / featured image -> saved to the draft. */
    public function update(Request $r, Page $page)
    {
        $data = $r->validate(['title' => ['required', 'string', 'max:160'], 'slug' => ['nullable', 'string', 'max:160'],
            'excerpt' => ['nullable', 'string', 'max:1000'], 'featured_media_id' => ['nullable', 'integer', 'exists:media,id']]);
        $work = PageDraft::working($page);
        $work['title'] = $data['title'];
        $work['excerpt'] = $data['excerpt'] ?? null;
        $work['featured_media_id'] = $data['featured_media_id'] ?? null;
        if (! $page->is_system) {
            $work['slug'] = PageDraft::validateSlug($data['slug'] ?? $page->slug, $page);
        }
        PageDraft::save($page, $work, $r->user());

        return back()->with('success', 'Saved to draft. Visitors still see the published version until you publish.');
    }

    public function visibility(Request $r, Page $page)
    {
        abort_if($page->slug === '_global', 404);
        $data = $r->validate(['status' => ['required', 'in:published,draft,scheduled'], 'publish_at' => ['nullable', 'date']]);
        if ($page->is_home && $data['status'] !== 'published') {
            throw ValidationException::withMessages(['status' => 'The homepage must stay published.']);
        }
        if ($data['status'] === 'scheduled' && (empty($data['publish_at']) || now()->gte($data['publish_at']))) {
            throw ValidationException::withMessages(['publish_at' => 'Choose a date and time in the future.']);
        }
        if ($data['status'] !== 'draft' && ! $page->sections()->exists()) {
            throw ValidationException::withMessages(['status' => 'Publish the page content first (the page has no published sections yet).']);
        }
        $page->update(['status' => $data['status'], 'publish_at' => $data['status'] === 'scheduled' ? $data['publish_at'] : null, 'updated_by' => $r->user()->id]);
        ActivityLogger::log('page.visibility', $page, "Set “{$page->title}” to {$data['status']}");

        return back()->with('success', 'Visibility updated.');
    }

    public function publish(Request $r, Page $page)
    {
        if (! $page->hasDraft()) {
            return back()->with('warning', 'There are no unpublished changes.');
        }
        PageDraft::publish($page, $r->user());
        $page->refresh();
        if ($r->boolean('go_live') && $page->status === 'draft' && $page->slug !== '_global') {
            $page->update(['status' => 'published']);
        }
        ActivityLogger::log('page.published', $page, "Published changes to “{$page->title}”");

        return redirect()->route('admin.pages.edit', $page)->with('success', $page->isPublic() ? 'Changes published. They are now live.' : 'Changes applied. The page itself is still hidden from visitors.');
    }

    public function discard(Request $r, Page $page)
    {
        PageDraft::discard($page);
        ActivityLogger::log('page.draft_discarded', $page, "Discarded draft of “{$page->title}”");

        return back()->with('success', 'Unpublished changes discarded.');
    }

    public function preview(Page $page)
    {
        $path = $page->slug === '_global' ? '/' : $page->path();

        return redirect(url($path) . '?preview=1');
    }

    public function duplicate(Request $r, Page $page)
    {
        abort_if($page->slug === '_global', 404);
        $work = PageDraft::working($page);
        $base = Str::before($page->slug, '/') === $page->slug ? $page->slug : Str::slug($page->title);
        $slug = $base . '-copy';
        for ($i = 2; Page::where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-copy-{$i}";
        }
        $copy = Page::create(['title' => $page->title . ' (copy)', 'slug' => $slug, 'template' => $page->template, 'status' => 'draft',
            'position' => (Page::max('position') ?? 0) + 1, 'created_by' => $r->user()->id, 'updated_by' => $r->user()->id]);
        foreach ($work['sections'] as &$s) {
            $s['uid'] = Str::lower(Str::random(8));
        }
        PageDraft::save($copy, ['title' => $copy->title, 'slug' => $slug, 'excerpt' => $work['excerpt'] ?? null,
            'featured_media_id' => $work['featured_media_id'] ?? null, 'sections' => $work['sections']], $r->user());
        ActivityLogger::log('page.duplicated', $copy, "Duplicated “{$page->title}”");

        return redirect()->route('admin.pages.edit', $copy)->with('success', 'Page duplicated as a draft.');
    }

    public function destroy(Page $page)
    {
        abort_if($page->is_system, 403, 'System pages cannot be deleted.');
        $title = $page->title;
        $page->delete();
        ActivityLogger::log('page.deleted', null, "Deleted page “{$title}”");

        return redirect()->route('admin.pages.index')->with('success', "“{$title}” deleted.");
    }

    public function restore(Request $r, Page $page, PageRevision $revision)
    {
        abort_unless($revision->page_id === $page->id, 404);
        PageDraft::restoreAsDraft($page, $revision, $r->user());
        ActivityLogger::log('page.revision_restored', $page, "Loaded an earlier version of “{$page->title}” into the draft");

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Earlier version loaded as a draft. Review it, then publish.');
    }
}
