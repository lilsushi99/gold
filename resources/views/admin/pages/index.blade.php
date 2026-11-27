@extends('layouts.admin')
@section('title', 'Website Pages')
@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
    <form method="get" class="d-flex gap-2 flex-wrap">
        <input class="form-control" style="width:260px" name="q" value="{{ request('q') }}" placeholder="Search pages…">
        <select class="form-select" style="width:210px" name="status" onchange="this.form.submit()">
            <option value="">All pages</option>
            @foreach (['published' => 'Published', 'draft' => 'Hidden / not published', 'scheduled' => 'Scheduled', 'pending' => 'With unpublished changes'] as $k => $l)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $l }}</option>@endforeach
        </select>
        <button class="btn btn-outline-secondary">Search</button>
    </form>
    <a class="btn btn-brand" href="{{ route('admin.pages.create') }}">+ New page</a>
</div>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
    <thead class="table-light"><tr><th>Page</th><th>Address</th><th>Status</th><th>Last updated</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    @forelse ($pages as $p)
        <tr>
            <td><a class="fw-semibold text-decoration-none" href="{{ route('admin.pages.edit', $p) }}">{{ $p->slug === '_global' ? 'Header, footer & side menu' : $p->title }}</a>
                @if ($p->is_home)<span class="badge text-bg-dark ms-1">Homepage</span>@endif
                @if ($p->slug === '_global')<span class="badge text-bg-secondary ms-1">Shown on every page</span>@endif
                @if ($p->hasDraft())<span class="badge badge-dev ms-1">Unpublished changes</span>@endif</td>
            <td class="text-muted small">{{ $p->slug === '_global' ? '-' : $p->path() }}</td>
            <td>@if ($p->slug === '_global')<span class="text-muted">-</span>
                @else<span class="badge text-bg-{{ ['published' => 'success', 'draft' => 'secondary', 'scheduled' => 'info'][$p->status] ?? 'secondary' }}">{{ ['published' => 'Published', 'draft' => 'Hidden', 'scheduled' => 'Scheduled'][$p->status] ?? $p->status }}</span>
                @if ($p->status === 'scheduled')<div class="small text-muted">{{ $p->publish_at?->format('d M Y H:i') }}</div>@endif @endif</td>
            <td class="small text-muted">{{ ($p->draft_updated_at ?? $p->updated_at)?->diffForHumans() }}</td>
            <td class="text-end text-nowrap">
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.pages.preview', $p) }}" target="_blank" rel="noopener">Preview</a>
                <a class="btn btn-sm btn-brand" href="{{ route('admin.pages.edit', $p) }}">Edit</a>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-center text-muted py-4">No pages match.</td></tr>
    @endforelse
    </tbody></table></div></div>
<div class="mt-3">{{ $pages->links('pagination::bootstrap-5') }}</div>
@endsection
