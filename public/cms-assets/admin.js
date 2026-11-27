/* Orisa CMS admin helpers (no build step, no dependencies besides Bootstrap's JS). */
(function () {
  'use strict';
  var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  /* ---------- confirm dialogs for destructive actions ---------- */
  var confirmModal, pendingForm;
  document.addEventListener('submit', function (e) {
    var f = e.target;
    var msg = f.getAttribute && f.getAttribute('data-confirm');
    if (msg && !f.__confirmed) {
      e.preventDefault();
      confirmModal = confirmModal || new bootstrap.Modal($('#cms-confirm'));
      $('#cms-confirm-text').textContent = msg;
      pendingForm = f;
      confirmModal.show();
      return;
    }
    if (f.hasAttribute && f.hasAttribute('data-loading')) {          // loading state
      $$('button[type=submit]', f).forEach(function (b) { b.disabled = true; b.dataset.label = b.textContent; b.textContent = 'Working…'; });
    }
  });
  document.addEventListener('click', function (e) {
    if (e.target.id === 'cms-confirm-yes' && pendingForm) { pendingForm.__confirmed = true; confirmModal.hide(); pendingForm.submit(); }
    var copy = e.target.closest('[data-copy]');
    if (copy) { var t = $(copy.getAttribute('data-copy')); t.select(); document.execCommand('copy'); copy.textContent = 'Copied'; setTimeout(function () { copy.textContent = 'Copy'; }, 1500); }
  });
  window.addEventListener('pageshow', function () {                  // re-enable after back navigation
    $$('button[type=submit][data-label]').forEach(function (b) { b.disabled = false; b.textContent = b.dataset.label; });
  });

  /* ---------- media picker ---------- */
  var picker = { cb: null, kind: 'image', page: 1, q: '', modal: null };
  function el(h) { var d = document.createElement('div'); d.innerHTML = h.trim(); return d.firstChild; }
  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]; }); }
  function tile(m) {
    var thumb = m.kind === 'image' ? '<img src="' + esc(m.url) + '" alt="">' : '<span class="badge text-bg-secondary">' + esc(m.ext.toUpperCase()) + '</span>';
    return el('<button type="button" class="card media-tile text-start p-0" data-id="' + m.id + '"><div class="media-thumb">' + thumb + '</div><div class="card-body p-2"><div class="small text-truncate">' + esc(m.name) + '</div></div></button>');
  }
  function loadPicker(reset) {
    if (reset) { picker.page = 1; $('#cms-picker-grid').innerHTML = ''; }
    var url = new URL($('#cms-picker').dataset.feed, location.origin);
    url.searchParams.set('page', picker.page); if (picker.kind) url.searchParams.set('kind', picker.kind); if (picker.q) url.searchParams.set('q', picker.q);
    fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' }).then(function (r) { return r.json(); }).then(function (j) {
      j.data.forEach(function (m) { var t = tile(m); t.__m = m; $('#cms-picker-grid').appendChild(t); });
      if (!j.data.length && reset) $('#cms-picker-grid').innerHTML = '<div class="text-muted p-3">Nothing found.</div>';
      var more = $('#cms-picker-more'); more.classList.toggle('d-none', !j.next); picker.page = j.next || picker.page;
    });
  }
  window.CmsMedia = {
    pick: function (opts, cb) {
      picker.cb = cb; picker.kind = (opts && opts.kind) === undefined ? 'image' : opts.kind; picker.q = '';
      $('#cms-picker-q').value = '';
      picker.modal = picker.modal || new bootstrap.Modal($('#cms-picker'));
      picker.modal.show(); loadPicker(true);
    }
  };
  document.addEventListener('click', function (e) {
    var t = e.target.closest('#cms-picker-grid .media-tile');
    if (t && picker.cb) { picker.cb(t.__m); picker.modal.hide(); }
    if (e.target.id === 'cms-picker-more') loadPicker(false);
    if (e.target.id === 'cms-picker-search') { picker.q = $('#cms-picker-q').value; loadPicker(true); }
  });
  document.addEventListener('keydown', function (e) { if (e.key === 'Enter' && e.target.id === 'cms-picker-q') { e.preventDefault(); picker.q = e.target.value; loadPicker(true); } });
  document.addEventListener('change', function (e) {
    if (e.target.id !== 'cms-picker-upload') return;
    var fd = new FormData(); $$('input[type=file]', e.target.parentNode).forEach(function (i) { Array.prototype.forEach.call(i.files, function (f) { fd.append('files[]', f); }); });
    var msg = $('#cms-picker-msg'); msg.textContent = 'Uploading…';
    fetch($('#cms-picker').dataset.upload, { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, credentials: 'same-origin' })
      .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
      .then(function (x) {
        msg.textContent = (x.j.errors || []).join(' ');
        if (x.j.uploaded && x.j.uploaded.length) { var m = x.j.uploaded[0]; if (picker.cb) { picker.cb(m); picker.modal.hide(); } }
        e.target.value = '';
      }).catch(function () { msg.textContent = 'Upload failed.'; });
  });

  /* ---------- image field widget ---------- */
  document.addEventListener('click', function (e) {
    var field = e.target.closest('.cms-image-field'); if (!field) return;
    var input = $('input[data-value]', field), img = $('img', field), empty = $('.cms-image-empty', field);
    function set(url, alt) {
      input.value = url || ''; if (img) { img.src = url ? (url.indexOf('assets/') === 0 ? '/' + url : url) : ''; img.classList.toggle('d-none', !url); }
      if (empty) empty.classList.toggle('d-none', !!url);
      var altInput = field.dataset.altTarget && $(field.dataset.altTarget); if (altInput && alt && !altInput.value) altInput.value = alt;
      input.dispatchEvent(new Event('change', { bubbles: true }));
    }
    if (e.target.closest('[data-pick]')) {
      var asId = field.hasAttribute('data-store-id');
      window.CmsMedia.pick({ kind: 'image' }, function (m) { set(asId ? String(m.id) : m.url, m.alt); if (asId) { img.src = m.url; img.classList.remove('d-none'); if (empty) empty.classList.add('d-none'); } });
    }
    if (e.target.closest('[data-clear]')) set('', '');
  });

  /* ---------- repeatable item groups ---------- */
  var SUFFIX_ATTRS = ['id', 'aria-controls', 'aria-labelledby', 'data-bs-target', 'for'];
  function renumber(group) {
    $$('.cms-item', group).forEach(function (item, i) {
      $$('[name]', item).forEach(function (n) { n.name = n.name.replace(/^(groups\[[^\]]+\])\[\d+\]/, '$1[' + i + ']'); });
      var num = $('.cms-item-num', item); if (num) num.textContent = i + 1;
    });
    var c = $('.cms-count', group); if (c) c.textContent = $$('.cms-item', group).length;
  }
  function clone(item, fresh) {
    var c = item.cloneNode(true), suffix = '-' + Math.random().toString(36).slice(2, 6);
    $$('input,textarea,select', c).forEach(function (n) {
      if (n.type === 'hidden') {
        if (n.dataset.attr && SUFFIX_ATTRS.indexOf(n.dataset.attr) > -1 && n.value) n.value += suffix;   // keep ids unique & consistent
      } else if (fresh && !n.closest('.cms-image-field')) { n.value = ''; }
    });
    return c;
  }
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-item-action]'); if (!b) return;
    var group = b.closest('.cms-group'), item = b.closest('.cms-item'), act = b.dataset.itemAction;
    if (act === 'add' || act === 'duplicate') {
      var src = act === 'duplicate' ? item : $$('.cms-item', group).pop();
      if (!src) return;
      var c = clone(src, false); (act === 'duplicate' ? item.after(c) : $('.cms-items', group).appendChild(c));
    }
    if (act === 'remove') { if ($$('.cms-item', group).length <= 1 && group.dataset.min !== '0') { alert('At least one item is needed.'); return; } item.remove(); }
    if (act === 'up' && item.previousElementSibling) item.parentNode.insertBefore(item, item.previousElementSibling);
    if (act === 'down' && item.nextElementSibling) item.parentNode.insertBefore(item.nextElementSibling, item);
    renumber(group);
  });
  document.addEventListener('submit', function (e) { $$('.cms-group', e.target).forEach(renumber); }, true);
})();
