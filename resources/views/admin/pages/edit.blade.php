@extends('layouts.admin')
@section('title', $isGlobal ? 'Header, footer & side menu' : $page->title)
@section('content')
@php
    $pending = $page->hasDraft();
    $featured = ! empty($work['featured_media_id']) ? \App\Models\Media::find($work['featured_media_id']) : null;
@endphp
<div class="d-flex flex-wrap gap-2 align-items-center mb-3">
    @unless ($isGlobal)
        <span class="badge text-bg-{{ ['published' => 'success', 'draft' => 'secondary', 'scheduled' => 'info'][$page->status] ?? 'secondary' }} fs-6">{{ ['published' => 'Published', 'draft' => 'Hidden from visitors', 'scheduled' => 'Scheduled'][$page->status] ?? $page->status }}</span>
        @if ($page->isPublic())<a class="small" href="{{ url($page->path()) }}" target="_blank" rel="noopener">View live page ↗</a>@endif
    @else
        <span class="text-muted">These blocks appear on every page of the website.</span>
    @endunless
    <span class="ms-auto d-flex gap-2">
        @unless ($isGlobal)
        <form method="post" action="{{ route('admin.pages.duplicate', $page) }}">@csrf<button class="btn btn-sm btn-outline-secondary">Duplicate page</button></form>
        @unless ($page->is_system)
        <form method="post" action="{{ route('admin.pages.destroy', $page) }}" data-confirm="Delete “{{ $page->title }}” permanently? This cannot be undone.">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
        @endunless @endunless
    </span>
</div>

@if ($pending)
<div class="alert alert-warning d-flex flex-wrap gap-3 align-items-center" role="status">
    <div class="me-auto"><strong>You have unpublished changes.</strong>
        <span class="text-muted">Saved {{ $page->draft_updated_at?->diffForHumans() }}. Visitors still see the {{ $page->isPublic() ? 'published version' : 'page as hidden' }}.</span></div>
    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.pages.preview', $page) }}" target="_blank" rel="noopener">Preview changes</a>
    <form method="post" action="{{ route('admin.pages.publish', $page) }}" class="d-flex gap-2 align-items-center">@csrf
        @if (! $isGlobal && $page->status === 'draft')<label class="small mb-0"><input type="checkbox" name="go_live" value="1" class="form-check-input" @checked(! $liveHasSections)> Also make the page visible</label>@endif
        <button class="btn btn-sm btn-brand" type="submit">Publish changes</button></form>
    <form method="post" action="{{ route('admin.pages.discard', $page) }}" data-confirm="Throw away all unpublished changes to this page?">@csrf<button class="btn btn-sm btn-link text-danger">Discard changes</button></form>
</div>
@else
<div class="alert alert-light border small mb-3">Everything is published. When you edit a section, your changes are saved as a <strong>draft</strong> first, so the live page is never half-edited.</div>
@endif

<div class="row g-3">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-2"><h2 class="h6 mb-0">Sections of this page <span class="text-muted">({{ count($work['sections']) }})</span></h2>
            @unless ($isGlobal)
            <form class="d-flex gap-2" method="post" action="{{ route('admin.sections.store', $page) }}">@csrf
                <select class="form-select form-select-sm" name="type" required style="width:260px"><option value="">Add a section…</option>
                    @foreach ($addable as $cat => $items)<optgroup label="{{ $cat === 'home' ? 'Homepage-style sections' : ucfirst($cat) }}">@foreach ($items as $d)<option value="{{ $d['type'] }}">{{ $d['label'] }}</option>@endforeach</optgroup>@endforeach</select>
                <button class="btn btn-sm btn-outline-dark">Add</button></form>
            @endunless</div>
        @forelse ($work['sections'] as $i => $s)
            @php $d = $defs[$s['type']] ?? null; $vis = $s['is_visible'] ?? true; @endphp
            <div class="card section-card mb-2 {{ $vis ? '' : 'is-hidden' }}"><div class="card-body py-2 d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted small" style="width:1.6rem">{{ $i + 1 }}</span>
                <div class="me-auto"><a class="fw-semibold text-decoration-none" href="{{ route('admin.sections.edit', [$page, $s['uid']]) }}">{{ $s['label'] ?: ($d['label'] ?? $s['type']) }}</a>
                    @unless ($vis)<span class="badge text-bg-secondary ms-1">Hidden</span>@endunless
                    <div class="small text-muted">{{ $d ? count($d['slots']) + collect($d['groups'])->sum(fn ($g) => count($g['items'])) . ' editable items' : 'Unknown section type' }}</div></div>
                <a class="btn btn-sm btn-brand" href="{{ route('admin.sections.edit', [$page, $s['uid']]) }}">Edit content</a>
                @unless ($isGlobal)
                <div class="btn-group btn-group-sm">
                    <form method="post" action="{{ route('admin.sections.move', [$page, $s['uid']]) }}">@csrf<input type="hidden" name="dir" value="up"><button class="btn btn-outline-secondary" title="Move up" @disabled($i === 0)>↑</button></form>
                    <form method="post" action="{{ route('admin.sections.move', [$page, $s['uid']]) }}">@csrf<input type="hidden" name="dir" value="down"><button class="btn btn-outline-secondary" title="Move down" @disabled($i === count($work['sections']) - 1)>↓</button></form>
                </div>
                <form method="post" action="{{ route('admin.sections.toggle', [$page, $s['uid']]) }}">@csrf<button class="btn btn-sm btn-outline-secondary">{{ $vis ? 'Hide' : 'Show' }}</button></form>
                <form method="post" action="{{ route('admin.sections.duplicate', [$page, $s['uid']]) }}">@csrf<button class="btn btn-sm btn-outline-secondary">Duplicate</button></form>
                <form method="post" action="{{ route('admin.sections.destroy', [$page, $s['uid']]) }}" data-confirm="Remove the section “{{ $s['label'] }}” from this page?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Remove</button></form>
                @endunless
            </div></div>
        @empty
            <div class="card p-4 text-center text-muted">This page has no sections yet. Use “Add a section” above.</div>
        @endforelse
    </div>
    <div class="col-lg-4">
        @unless ($isGlobal)
        <form class="card mb-3" method="post" action="{{ route('admin.pages.update', $page) }}" data-loading>@csrf @method('PUT')
            <div class="card-header bg-white fw-semibold">Page details</div>
            <div class="card-body">
                <div class="mb-3"><label class="form-label small fw-semibold">Page title</label><input class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $work['title']) }}" required maxlength="160">@error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                @if ($page->is_system)<div class="mb-3 small text-muted">Address: <code>{{ $page->path() }}</code> (fixed for this page)</div>
                @else<div class="mb-3"><label class="form-label small fw-semibold">Web address</label><div class="input-group input-group-sm"><span class="input-group-text">/</span><input class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug', $work['slug']) }}" required></div>@error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
                    <div class="form-text">Changing it after publishing breaks old links - add a redirect afterwards.</div></div>@endif
                <div class="mb-3"><label class="form-label small fw-semibold">Short summary</label><textarea class="form-control form-control-sm" name="excerpt" rows="2" maxlength="1000">{{ old('excerpt', $work['excerpt'] ?? '') }}</textarea></div>
                <div class="mb-3"><label class="form-label small fw-semibold">Featured image</label>
                    <div class="cms-image-field" data-store-id>
                        <img src="{{ $featured?->url }}" class="{{ $featured ? '' : 'd-none' }} mb-2" alt="">
                        <div class="cms-image-empty small text-muted mb-2 {{ $featured ? 'd-none' : '' }}">No image selected</div>
                        <input type="hidden" name="featured_media_id" value="{{ $work['featured_media_id'] ?? '' }}" data-value>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-pick>Choose image</button>
                        <button type="button" class="btn btn-sm btn-link text-danger" data-clear>Remove</button></div></div>
                <button class="btn btn-sm btn-brand" type="submit">Save to draft</button>
            </div>
        </form>
        <form class="card mb-3" method="post" action="{{ route('admin.pages.visibility', $page) }}" data-loading>@csrf @method('PUT')
            <div class="card-header bg-white fw-semibold">Visibility</div>
            <div class="card-body">
                @error('status')<div class="alert alert-danger py-2 small">{{ $message }}</div>@enderror
                @foreach (['published' => 'Published - visible to everyone', 'draft' => 'Hidden - only admins can preview', 'scheduled' => 'Scheduled - goes live at a set time'] as $v => $l)
                    <div class="form-check"><input class="form-check-input" type="radio" name="status" id="st-{{ $v }}" value="{{ $v }}" @checked(old('status', $page->status) === $v) @disabled($page->is_home && $v !== 'published')><label class="form-check-label small" for="st-{{ $v }}">{{ $l }}</label></div>
                @endforeach
                <input type="datetime-local" class="form-control form-control-sm mt-2 @error('publish_at') is-invalid @enderror" name="publish_at" value="{{ old('publish_at', $page->publish_at?->format('Y-m-d\TH:i')) }}">@error('publish_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <button class="btn btn-sm btn-outline-dark mt-3" type="submit">Update visibility</button>
            </div>
        </form>
        @endunless
        <div class="card"><div class="card-header bg-white fw-semibold">Earlier versions</div>
            <ul class="list-group list-group-flush small">
                @forelse ($revisions as $rev)
                    <li class="list-group-item d-flex justify-content-between align-items-center"><span>{{ $rev->created_at?->format('d M Y H:i') }}<br><span class="text-muted">{{ $rev->author?->name ?? 'System' }}</span></span>
                        <form method="post" action="{{ route('admin.pages.restore', [$page, $rev]) }}" data-confirm="Load this earlier version into your draft? Your current unpublished changes will be replaced.">@csrf<button class="btn btn-sm btn-outline-secondary">Restore</button></form></li>
                @empty<li class="list-group-item text-muted">A copy of the live page is kept here every time you publish.</li>@endforelse
            </ul></div>
    </div>
</div>
@endsection
