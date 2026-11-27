<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\ActivityLogger;
use App\Services\MediaService;
use App\Services\MediaUsage;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MediaController extends Controller
{
    public function __construct(private MediaService $media) {}

    private function query(Request $r)
    {
        return Media::query()
            ->when($r->filled('kind'), fn ($q) => $q->where('kind', $r->string('kind')))
            ->when($r->filled('q'), function ($q) use ($r) {
                $t = '%' . addcslashes($r->string('q'), '%_\\') . '%';
                $q->where(fn ($q) => $q->where('original_name', 'like', $t)->orWhere('title', 'like', $t)->orWhere('alt_text', 'like', $t));
            })
            ->latest('id');
    }

    public function index(Request $r)
    {
        return view('admin.media.index', [
            'items' => $this->query($r)->paginate(24)->withQueryString(),
            'maxMb' => round(config('cms.media.max_kb') / 1024, 1),
            'accept' => '.' . implode(',.', array_keys(config('cms.media.types'))),
            'breadcrumbs' => ['Media Library' => null],
        ]);
    }

    /** JSON feed for the media picker modal. */
    public function picker(Request $r)
    {
        $page = $this->query($r)->paginate(24);

        return response()->json([
            'data' => $page->getCollection()->map(fn (Media $m) => $this->payload($m))->values(),
            'next' => $page->hasMorePages() ? $page->currentPage() + 1 : null,
        ]);
    }

    private function payload(Media $m): array
    {
        return ['id' => $m->id, 'url' => $m->url, 'name' => $m->original_name, 'title' => $m->title, 'alt' => $m->alt_text,
            'kind' => $m->kind, 'ext' => $m->extension, 'width' => $m->width, 'height' => $m->height, 'size' => $m->size];
    }

    public function store(Request $r)
    {
        $r->validate(['files' => ['required', 'array', 'max:20'], 'files.*' => ['file']]);
        $done = [];
        $errors = [];
        foreach ($r->file('files') as $file) {
            try {
                $m = $this->media->store($file, $r->user()->id);
                ActivityLogger::log('media.uploaded', $m, "Uploaded {$m->original_name}");
                $done[] = $m;
            } catch (ValidationException $e) {
                $errors[] = $e->errors()['file'][0] ?? 'Upload failed.';
            }
        }
        if ($r->expectsJson()) {
            return response()->json(['uploaded' => collect($done)->map(fn ($m) => $this->payload($m)), 'errors' => $errors], $done ? 200 : 422);
        }
        $back = back();
        if ($done) {
            $back->with('success', count($done) . ' file(s) uploaded.');
        }

        return $errors ? $back->with('error', implode(' ', $errors)) : $back;
    }

    public function edit(Media $media)
    {
        return view('admin.media.edit', [
            'media' => $media, 'usage' => MediaUsage::for($media),
            'breadcrumbs' => ['Media Library' => route('admin.media.index'), $media->original_name => null],
        ]);
    }

    public function update(Request $r, Media $media)
    {
        $data = $r->validate([
            'alt_text' => ['nullable', 'string', 'max:255'], 'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'], 'replacement' => ['nullable', 'file'],
        ]);
        $media->update(collect($data)->only(['alt_text', 'title', 'description'])->all());
        if ($r->hasFile('replacement')) {
            $old = $media->extension;
            $media = $this->media->replace($media, $r->file('replacement'), $r->user()->id);
            $note = $old !== $media->extension ? ' The file type changed, so existing references to the old file name may need updating.' : '';
            ActivityLogger::log('media.replaced', $media, "Replaced file of {$media->original_name}");
            return redirect()->route('admin.media.edit', $media)->with('success', 'Image details saved and file replaced.' . $note);
        }
        ActivityLogger::log('media.updated', $media, "Updated details of {$media->original_name}");

        return back()->with('success', 'Details saved.');
    }

    public function destroy(Media $media)
    {
        $usage = MediaUsage::for($media);
        if ($usage) {
            return back()->with('error', 'This file is still in use and cannot be deleted: ' . implode('; ', array_slice($usage, 0, 5)) . (count($usage) > 5 ? '…' : ''));
        }
        $name = $media->original_name;
        $this->media->delete($media);
        ActivityLogger::log('media.deleted', null, "Deleted {$name}");

        return redirect()->route('admin.media.index')->with('success', "{$name} deleted.");
    }
}
