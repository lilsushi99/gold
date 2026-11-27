@extends('layouts.admin')
@section('title', 'Media Library')
@section('content')
<div class="card mb-3"><div class="card-body">
    <form method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="row g-2 align-items-end" data-loading>@csrf
        <div class="col-md">
            <label class="form-label fw-semibold" for="files">Upload files</label>
            <input id="files" class="form-control" type="file" name="files[]" multiple accept="{{ $accept }}" required>
            <div class="form-text">Images (JPG, PNG, WebP, GIF, SVG), PDF, Office documents and MP4/WebM video. Up to {{ $maxMb }} MB each; very large photos are scaled down automatically.</div>
        </div>
        <div class="col-md-auto"><button class="btn btn-brand" type="submit">Upload</button></div>
    </form>
</div></div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-5"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search by name, title or alt text…"></div>
    <div class="col-md-3"><select class="form-select" name="kind" onchange="this.form.submit()">
        <option value="">All file types</option>
        @foreach (['image' => 'Images', 'document' => 'Documents', 'video' => 'Videos'] as $k => $l)<option value="{{ $k }}" @selected(request('kind') === $k)>{{ $l }}</option>@endforeach
    </select></div>
    <div class="col-auto"><button class="btn btn-outline-secondary">Search</button> @if (request()->hasAny(['q','kind']))<a class="btn btn-link" href="{{ route('admin.media.index') }}">Clear</a>@endif</div>
</form>

<div class="row g-3">
    @forelse ($items as $m)
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a class="card h-100 text-decoration-none text-reset media-tile" href="{{ route('admin.media.edit', $m) }}">
                <div class="media-thumb">
                    @if ($m->isImage())<img src="{{ $m->url }}" alt="{{ $m->alt_text }}" loading="lazy">
                    @else<span class="badge text-bg-secondary fs-6">{{ strtoupper($m->extension) }}</span>@endif
                </div>
                <div class="card-body p-2"><div class="small text-truncate fw-semibold" title="{{ $m->original_name }}">{{ $m->original_name }}</div>
                    <div class="small text-muted">{{ number_format($m->size / 1024) }} KB @if ($m->width)· {{ $m->width }}×{{ $m->height }}@endif</div>
                    @if ($m->isImage() && ! $m->alt_text)<span class="badge text-bg-warning">No alt text</span>@endif</div>
            </a>
        </div>
    @empty
        <div class="col-12"><div class="card p-4 text-center text-muted">No files found. Upload your first image above.</div></div>
    @endforelse
</div>
<div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
@endsection
