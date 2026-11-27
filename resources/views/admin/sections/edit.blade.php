@extends('layouts.admin')
@section('title', $section['label'] ?: $def['label'])
@section('content')
@php $isGlobal = $page->slug === '_global'; @endphp
<datalist id="page-paths">
    @foreach ($pagePaths as $p)<option value="{{ $p->is_home ? '/' : '/' . $p->slug }}">{{ $p->title }}</option>@endforeach
</datalist>
<form method="post" action="{{ route('admin.sections.update', [$page, $uid]) }}" data-loading>@csrf @method('PUT')
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3 sticky-actions">
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.pages.edit', $page) }}">← Back to page</a>
        <span class="text-muted small me-auto">Changes are saved as a draft - the live site does not change until you publish.</span>
        <button class="btn btn-brand" type="submit">Save draft</button>
        <button class="btn btn-outline-dark" type="submit" name="after" value="preview" formtarget="_blank">Save &amp; preview</button>
    </div>
    @if ($errors->any())<div class="alert alert-danger"><strong>Please fix the highlighted problems:</strong><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    @unless ($isGlobal)
    <div class="card mb-3"><div class="card-body"><label class="form-label small fw-semibold" for="label">Section name <span class="text-muted fw-normal">(only you see this, to tell sections apart)</span></label>
        <input id="label" class="form-control" name="label" value="{{ old('label', $section['label']) }}" maxlength="120"></div></div>
    @endunless

    @php $plain = collect($def['slots'])->filter(fn ($s) => $s['type'] !== 'hidden'); @endphp
    @if ($plain->isNotEmpty())
    <div class="card mb-3"><div class="card-header bg-white fw-semibold">Text, images and links</div>
        <div class="card-body"><div class="row">
        @foreach ($def['slots'] as $slot)
            <div class="{{ in_array($slot['type'], ['textarea']) ? 'col-12' : 'col-md-6' }}">
                @include('admin.sections._field', ['slot' => $slot, 'name' => "slots[{$slot['key']}]", 'value' => old("slots.{$slot['key']}", $values['slots'][$slot['key']] ?? ''), 'fid' => 'f-' . $slot['key']])
            </div>
        @endforeach
        </div></div></div>
    @endif

    @foreach ($def['groups'] as $g)
        @php $items = $values['groups'][$g['key']] ?? []; $first = collect($g['slots'])->first(fn ($s) => in_array($s['type'], ['text', 'textarea'])); @endphp
        <div class="card mb-3 cms-group" data-min="1">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Repeating items <span class="text-muted fw-normal">(<span class="cms-count">{{ count($items) }}</span>)</span></span>
                <button type="button" class="btn btn-sm btn-outline-dark" data-item-action="add">+ Add item</button></div>
            <div class="card-body cms-items d-grid gap-3">
            @foreach ($items as $n => $item)
                <div class="cms-item">
                    <div class="cms-item-head d-flex align-items-center gap-2 px-3 py-2">
                        <span class="badge text-bg-dark">#<span class="cms-item-num">{{ $n + 1 }}</span></span>
                        <span class="small text-muted text-truncate me-auto">{{ $first ? \Illuminate\Support\Str::limit($item[$first['key']] ?? '', 60) : '' }}</span>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary" data-item-action="up" title="Move up">↑</button>
                            <button type="button" class="btn btn-outline-secondary" data-item-action="down" title="Move down">↓</button>
                            <button type="button" class="btn btn-outline-secondary" data-item-action="duplicate">Duplicate</button>
                            <button type="button" class="btn btn-outline-danger" data-item-action="remove">Remove</button></div>
                    </div>
                    <div class="p-3"><div class="row">
                    @foreach ($g['slots'] as $slot)
                        <div class="{{ $slot['type'] === 'hidden' ? '' : (in_array($slot['type'], ['textarea']) ? 'col-12' : 'col-md-6') }}">
                            @include('admin.sections._field', ['slot' => $slot, 'name' => "groups[{$g['key']}][{$n}][{$slot['key']}]", 'value' => old("groups.{$g['key']}.{$n}.{$slot['key']}", $item[$slot['key']] ?? ''), 'fid' => "f-{$g['key']}-{$n}-{$slot['key']}"])
                        </div>
                    @endforeach
                    </div></div>
                </div>
            @endforeach
            </div>
        </div>
    @endforeach

    <div class="d-flex gap-2 mb-5">
        <button class="btn btn-brand" type="submit">Save draft</button>
        <button class="btn btn-outline-dark" type="submit" name="after" value="preview" formtarget="_blank">Save &amp; preview</button>
    </div>
</form>
@endsection
