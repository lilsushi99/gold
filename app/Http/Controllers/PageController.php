<?php

namespace App\Http\Controllers;

use App\Cms\SectionRenderer;
use App\Models\Page;
use App\Services\SeoService;

class PageController extends Controller
{
    public function home()
    {
        return $this->render(Page::where('is_home', true)->firstOrFail());
    }

    public function show(string $slug)
    {
        // The homepage has one canonical URL: "/".
        if ($slug === 'home') {
            return redirect('/', 301);
        }

        return $this->render(Page::where('slug', $slug)->where('slug', '!=', '_global')->firstOrFail());
    }

    private function render(Page $page)
    {
        $user = auth()->user();
        $preview = request()->boolean('preview') && $user?->hasPermission('pages.manage');

        // Drafts / unpublished / future-scheduled pages are invisible to the public (404, not 403).
        abort_unless($page->isPublic() || $preview, 404);

        $page->loadMissing('seo');
        $sections = $page->sections()->where('is_visible', true)->orderBy('position')->get();

        $globals = Page::where('slug', '_global')->first()?->sections->keyBy('type') ?? collect();

        return response()->view('layouts.app', [
            'page' => $page,
            'seo' => SeoService::forPage($page),
            'body' => $sections->map(fn ($s) => SectionRenderer::render($s))->implode("\n"),
            'globals' => $globals->map(fn ($s) => SectionRenderer::render($s)),
            'preview' => $preview,
        ])->header('X-Robots-Tag', $preview ? 'noindex' : null);
    }
}
