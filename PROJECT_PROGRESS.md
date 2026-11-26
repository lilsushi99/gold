# PROJECT_PROGRESS.md (handover - read README.md and PROJECT_ARCHITECTURE.md first)

Branch: `laravel-cms` (never push unfinished work to `main`).

## Environment notes for the next session
- Sandbox has **no packagist access** -> Laravel deps were installed from GitHub via `tools/offline-composer/` (see its README).
  If `vendor/` is missing: rebuild it with that workflow (takes ~3 min) or copy a previous `vendor/`.
- PHP 8.3 + MariaDB 10.11 installed via apt (`apt-get install php-cli php-mbstring php-xml php-curl php-zip php-mysql php-gd php-intl composer mariadb-server unrar unar`).
- Background processes die between tool calls: start MariaDB (`mysqld_safe`) and `php artisan serve` with `setsid nohup ... &` in the SAME call that uses them.
  Never `pkill -f <text>` (it matches the calling shell). Kill by port/PID instead.
- Local dev DB: `orisa_cms` / user `orisa` (credentials only in the untracked `.env`).
- Do **not** create a physical `public/admin/` folder: it collides with the `/admin` routes (admin assets live in `public/cms-assets/`).

## Completed
- [x] Phase 1: audit + architecture.
- [x] Phase 2 Laravel foundation: Laravel 12.74 (PHP >= 8.2, platform pinned to 8.2), 9 migrations (30 tables incl. cache/sessions),
      22 Eloquent models, idempotent seeders (3 roles/16 permissions, initial admin, 49 default settings).
- [x] SQL export `database/sql/complete_database.sql` - verified: imports into a fresh DB with identical structure (30 tables, 26 FKs, 98 index cols)
      and data; `php artisan migrate` then reports "Nothing to migrate"; re-seeding creates no duplicates. Regenerate with `tools/export-sql.sh`.
- [x] Phase 3 (partial): admin auth - login (rate limited, 429 lockout), logout, forced first-login password change, forgot/reset password,
      `permission:` and `password.changed` middleware, activity logger (no secrets), admin layout (sidebar filtered by permission, topbar, breadcrumbs, flash), dashboard.
      Verified by `tests/manual/auth_flow.py` (16/16 checks pass).

## Initial dev credentials (DEV ONLY - change before production)
Email `admin@orisa.test`; password is reported in the session hand-off message (generated, not stored in git).
Seeder reads `ADMIN_EMAIL` / `ADMIN_PASSWORD` from `.env`; the SQL export contains the dev account hash.
- [x] Phase 4 (homepage): frontend served through Laravel from the database.
      * `tools/cms/slotify.py` + `tools/cms/build_pages.py` convert original HTML into Blade section templates (`resources/views/sections/*.blade.php`)
        and JSON definitions (`resources/cms/sections/*.json`: slot labels/types + original defaults + auto-detected repeating groups).
        Every visible text, image, link, alt text and background image is a named *slot*; repeated cards/list items are *groups* (add/remove/reorder-able).
        Static parts that differ between items (SVG icons, ids, aria-labels) become hidden per-item values. Original markup is otherwise untouched.
      * 17 section types: 4 global blocks (search, header, offcanvas, footer) + 13 homepage sections. `ContentSeeder` imports them into
        `pages` / `page_sections` (`content` JSON = {slots:{}, groups:{}}), never overwriting existing pages.
      * `PageController` (home, `/{slug}`), `layouts/app.blade.php`, `SeoService` (title/description/canonical/robots/OG/Twitter with global fallbacks),
        analytics/GTM/verification head+body partials, DB-driven `<x-cms-menu>` component (dropdown/mega-menu markup from the template),
        `cms_url()` + `setting()` helpers, drafts/scheduled pages are 404 for the public, `?preview=1` for admins with `pages.manage`.
      * Dark/light: the template already has a working switch (`color-modes.js`); dark `-dark.html` twins differ only by `data-bs-theme="dark"` and one class on `<main>`.
        The layout applies the visitor's saved choice (or admin setting `default_theme`) before paint; dark `<main>` background is transparent like the original.
      * Verified: `tests/manual/parity.py` -> **homepage body identical to original `index.html` (1419/1419 elements, attrs and text)**;
        `tests/manual/public_site.py` 16/16 (assets load, DB edit visible, hidden section, draft/scheduled 404, preview, redirects); `auth_flow.py` 16/16.
      * Assets: `tools/sync_assets.py <pages...>` copies css/js/fonts fully + only images used by the listed pages into `public/assets` (19 MB for the homepage).

## NOT done yet (do not assume any of this exists)
- Other public pages are NOT converted yet (about, services, portfolio list + details, blog list + details, team, pricing, faqs, contact, 404). Links to them in
  the homepage/footers currently 404 (hrefs were mapped to clean URLs in `URL_MAP` of build_pages.py: /about, /services, /portfolio, /blog, /contact ...).
  Next: add them to `build_pages.py` (choose ONE variant each; portfolio layout still to choose), run `tools/sync_assets.py index.html about-1.html ...`, rebuild, reseed.
- Header/footer link lists and social icons are plain slots/groups, not yet bound to menus/settings (footer menus, contact email/phone, social URLs -> `settings`). Primary menu = only "Home".
- No admin UI yet for pages/sections (slot editor, group add/remove/reorder, hide/reorder sections, add section types to a page, duplicate, revisions UI), media library, menus,
  posts/categories/tags, services/projects/team/testimonials/faqs/pricing modules, enquiries + working forms (all template forms are still `action="#"`), settings, users/roles,
  activity-log viewer. Sidebar links stay hidden until their routes exist.
- Portfolio/blog/team detail pages need DB models-driven rendering (tables exist: projects, posts, team_members).
- SEO admin: previews, sitemap.xml, robots.txt endpoint + editor, redirects middleware, `.html` legacy URL 301s, structured data (Organisation/Breadcrumb/Article), noindex handling in sitemap.
- Group "duplicate item" must suffix hidden id-like values (id/aria-controls/data-bs-target/for) to keep accordions working (design: append same suffix to every hidden slot value with attr in that list).
- Image upload/resize/WebP, SVG sanitising, file validation; Backup/DB tools page.
- Tests: only manual HTTP scripts in `tests/manual/`; phpunit not installed in sandbox vendor.
- Docs: CPANEL_DEPLOYMENT.md, DATABASE_GUIDE.md, ADMIN_GUIDE.md still to write.

## Decisions (from client)
One homepage = light `index.html` + a real dark/light switch (inspect dark twins first: keep structural differences as components);
one representative layout per inner page type; portfolio = one chosen layout, database-driven; NO e-commerce (shop pages archived, not routed);
admin must manage all meaningful content of every selected page; admins can create new pages from reusable layouts/section components.

## Exact next task
1. Phase 3/5 (admin for what already exists): `Admin\PageController` (list/create/duplicate/delete/publish/schedule/preview, slug, template),
   `Admin\SectionController` + slot editor view generated from `SectionRegistry::find($type)` (text/textarea/url/image(media picker)/alt inputs, hidden slots not shown,
   groups with add/duplicate/remove/reorder), section hide/show + reorder + add-from-registry, revisions snapshot on save (`page_revisions`), activity log entries, `permission:` middleware.
2. Admin: media library (needed by the image slot picker), global settings (bind footer/offcanvas contact+social slots), menus editor.
3. Then convert remaining pages (see NOT done yet), SEO admin + sitemap/robots/redirects, forms, modules, docs, final testing + SQL regeneration (`tools/export-sql.sh`).
