<div class="modal fade" id="cms-confirm" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Please confirm</h5><button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
    <div class="modal-body" id="cms-confirm-text"></div>
    <div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button class="btn btn-danger" id="cms-confirm-yes" type="button">Yes, continue</button></div>
</div></div></div>
@if (auth()->user()->hasPermission('media.manage') || auth()->user()->hasPermission('pages.manage') || auth()->user()->hasPermission('sections.manage'))
<div class="modal fade" id="cms-picker" tabindex="-1" aria-hidden="true" data-feed="{{ route('admin.media.picker', [], false) }}" data-upload="{{ route('admin.media.store', [], false) }}">
  <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Choose an image</h5><button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
    <div class="modal-body">
      <div class="row g-2 mb-3 align-items-center">
        <div class="col-md-5"><input id="cms-picker-q" class="form-control" placeholder="Search your files…"></div>
        <div class="col-auto"><button id="cms-picker-search" class="btn btn-outline-secondary" type="button">Search</button></div>
        @if (auth()->user()->hasPermission('media.manage'))
        <div class="col-md ms-auto text-md-end"><label class="btn btn-brand mb-0">Upload new image<input id="cms-picker-upload" type="file" accept=".jpg,.jpeg,.png,.gif,.webp,.svg" hidden></label></div>
        @endif
      </div>
      <div id="cms-picker-msg" class="text-danger small mb-2"></div>
      <div id="cms-picker-grid" class="d-grid gap-3" style="grid-template-columns:repeat(auto-fill,minmax(140px,1fr))"></div>
      <div class="text-center mt-3"><button id="cms-picker-more" class="btn btn-light d-none" type="button">Load more</button></div>
    </div>
  </div></div>
</div>
@endif
