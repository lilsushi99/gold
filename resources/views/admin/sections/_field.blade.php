{{-- Renders one editable field. Vars: $slot (definition), $name (input name), $value, $fid (unique id) --}}
@php $t = $slot['type']; @endphp
@if ($t === 'hidden')
    <input type="hidden" name="{{ $name }}" value="{{ $value }}" data-attr="{{ $slot['attr'] ?? '' }}">
@else
<div class="mb-3">
    <label class="form-label small fw-semibold" for="{{ $fid }}">{{ $slot['label'] }}</label>
    @if ($t === 'image')
        <div class="cms-image-field">
            <img src="{{ $value ? cms_url($value) : '' }}" class="{{ $value ? '' : 'd-none' }} mb-2 d-block" alt="">
            <div class="cms-image-empty small text-muted mb-2 {{ $value ? 'd-none' : '' }}">No image</div>
            <input type="hidden" id="{{ $fid }}" name="{{ $name }}" value="{{ $value }}" data-value>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-pick>Choose or upload image</button>
            <button type="button" class="btn btn-sm btn-link text-danger" data-clear>Remove</button>
        </div>
    @elseif ($t === 'textarea')
        <textarea id="{{ $fid }}" class="form-control" name="{{ $name }}" rows="4" maxlength="5000">{{ $value }}</textarea>
    @elseif ($t === 'url')
        <input id="{{ $fid }}" class="form-control" name="{{ $name }}" value="{{ $value }}" maxlength="2000" list="page-paths" placeholder="https://… or choose a page">
    @elseif ($t === 'alt')
        <input id="{{ $fid }}" class="form-control" name="{{ $name }}" value="{{ $value }}" maxlength="500">
        <div class="form-text">Describes the image for search engines and screen readers.</div>
    @else
        <input id="{{ $fid }}" class="form-control" name="{{ $name }}" value="{{ $value }}" maxlength="5000">
    @endif
    @error(Str::of($name)->replace(['[', ']'], ['.', ''])->toString())<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>
@endif
