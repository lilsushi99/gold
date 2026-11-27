@extends('layouts.admin')
@section('title', 'New page')
@section('content')
<form class="card" style="max-width:640px" method="post" action="{{ route('admin.pages.store') }}" data-loading>@csrf
    <div class="card-body">
        <div class="mb-3"><label class="form-label fw-semibold" for="title">Page title</label>
            <input id="title" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required maxlength="160" autofocus>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="mb-3"><label class="form-label fw-semibold" for="slug">Web address</label>
            <div class="input-group"><span class="input-group-text">{{ url('/') }}/</span>
                <input id="slug" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug') }}" required maxlength="160" placeholder="our-story"></div>
            @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            <div class="form-text">Lowercase letters, numbers and hyphens. Filled in automatically from the title.</div></div>
        <div class="mb-3"><label class="form-label fw-semibold" for="copy_from">Start from</label>
            <select id="copy_from" name="copy_from" class="form-select"><option value="">A blank page (add sections myself)</option>
                @foreach ($sources as $s)<option value="{{ $s->id }}" @selected(old('copy_from') == $s->id)>Copy the sections of “{{ $s->title }}”</option>@endforeach</select>
            <div class="form-text">The new page is created as a hidden draft. Nothing is visible to visitors until you publish it.</div></div>
        <button class="btn btn-brand" type="submit">Create page</button> <a class="btn btn-link" href="{{ route('admin.pages.index') }}">Cancel</a>
    </div>
</form>
@push('scripts')<script>
(function(){var t=document.getElementById('title'),s=document.getElementById('slug'),touched=false;s.addEventListener('input',function(){touched=true});
t.addEventListener('input',function(){if(!touched)s.value=t.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/[^a-z0-9]+/g,'-').replace(/^-+|-+$/g,'')})})();
</script>@endpush
@endsection
