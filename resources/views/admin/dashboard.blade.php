@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <div class="row g-3 mb-4">
        @foreach ($stats as $label => $value)
            <div class="col-6 col-lg"><div class="card p-3"><div class="stat">{{ $value }}</div><div class="text-muted small mt-1">{{ $label }}</div></div></div>
        @endforeach
    </div>
    <div class="card">
        <div class="card-header bg-white fw-semibold">Recent activity</div>
        <ul class="list-group list-group-flush">
            @forelse ($recent as $log)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $log->description ?? $log->action }} <span class="text-muted">- {{ $log->user?->name ?? 'System' }}</span></span>
                    <span class="text-muted small">{{ $log->created_at?->diffForHumans() }}</span>
                </li>
            @empty
                <li class="list-group-item text-muted">No activity yet.</li>
            @endforelse
        </ul>
    </div>
@endsection
