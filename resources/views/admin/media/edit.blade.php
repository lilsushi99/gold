@extends('layouts.admin')
@section('title', 'Edit file')
@section('content')
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card"><div class="card-body text-center bg-light" style="min-height:260px;display:grid;place-items:center">
            @if ($media->isImage())<img src="{{ $media->url }}" alt="{{ $media->alt_text }}" style="max-width:100%;max-height:480px">
            @elseif ($media->kind === 'video')<video src="{{ $media->url }}" controls style="max-width:100%"></video>
            @else<div><span class="badge text-bg-secondary fs-4">{{ strtoupper($media->extension) }}</span><div class="mt-2"><a href="{{ $media->url }}" target="_blank" rel="noopener">Open file</a></div></div>@endif
        </div></div>
        <div class="card mt-3"><div class="card-body">
            <div class="fw-semibold mb-1">File URL</div>
            <div class="input-group"><input class="form-control" readonly id="media-url" value="{{ url($media->url) }}">
                <button class="btn btn-outline-secondary" type="button" data-copy="#media-url">Copy</button></div>
            <dl class="row small mt-3 mb-0">
                <dt class="col-4">File</dt><dd class="col-8">{{ $media->original_name }}</dd>
                <dt class="col-4">Type</dt><dd class="col-8">{{ $media->mime_type }}</dd>
                <dt class="col-4">Size</dt><dd class="col-8">{{ number_format($media->size / 1024, 1) }} KB</dd>
                @if ($media->width)<dt class="col-4">Dimensions</dt><dd class="col-8">{{ $media->width }} × {{ $media->height }} px</dd>@endif
                <dt class="col-4">Uploaded</dt><dd class="col-8">{{ $media->created_at->format('d M Y H:i') }} @if ($media->uploader)by {{ $media->uploader->name }}@endif</dd>
            </dl>
        </div></div>
    </div>
    <div class="col-lg-5">
        <form class="card" method="post" action="{{ route('admin.media.update', $media) }}" enctype="multipart/form-data" data-loading>@csrf @method('PUT')
            <div class="card-body">
                <div class="mb-3"><label class="form-label fw-semibold">Alternative text <span class="text-muted fw-normal">(describes the image for search engines and screen readers)</span></label>
                    <input class="form-control" name="alt_text" maxlength="255" value="{{ old('alt_text', $media->alt_text) }}"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Title</label><input class="form-control" name="title" maxlength="255" value="{{ old('title', $media->title) }}"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Description</label><textarea class="form-control" name="description" rows="3" maxlength="2000">{{ old('description', $media->description) }}</textarea></div>
                <div class="mb-3"><label class="form-label fw-semibold">Replace file</label><input class="form-control" type="file" name="replacement" accept=".{{ $media->extension }}">
                    <div class="form-text">Uploads a new version. The same file type keeps every place it is used working.</div></div>
                <button class="btn btn-brand" type="submit">Save changes</button>
            </div>
        </form>
        <div class="card mt-3"><div class="card-body">
            <div class="fw-semibold mb-2">Where this file is used</div>
            @if ($usage)<ul class="small mb-0">@foreach ($usage as $u)<li>{{ $u }}</li>@endforeach</ul>
            @else<p class="small text-muted mb-0">Not used anywhere yet.</p>@endif
        </div></div>
        <form class="mt-3" method="post" action="{{ route('admin.media.destroy', $media) }}" data-confirm="Delete {{ $media->original_name }}? This cannot be undone.">@csrf @method('DELETE')
            <button class="btn btn-outline-danger" type="submit" @disabled($usage)>Delete file</button>
            @if ($usage)<span class="small text-muted ms-2">Remove it from the places above first.</span>@endif
        </form>
    </div>
</div>
@endsection
