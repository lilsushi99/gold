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

## NOT done yet (do not assume any of this exists)
- Public site: still a placeholder at `/`. No Blade conversion, no `public/assets` copy of the template, no dark/light switch yet (Phase 4).
- Admin CRUD modules: pages, section editors, media library, menus, posts/categories/tags, services/projects/team/testimonials/faqs/pricing,
  enquiries, settings, users/roles, activity log viewer (sidebar links are hidden until their routes exist).
- SEO system (head rendering, previews, sitemap, robots.txt editor, redirects, structured data), forms (contact), revisions UI, content import seeder.
- Tests beyond the manual HTTP script (phpunit is not installed in the sandbox vendor; add to the offline closure if wanted).
- README/CPANEL_DEPLOYMENT.md/DATABASE_GUIDE.md/ADMIN_GUIDE.md (only README + this file + ARCHITECTURE exist).

## Decisions (from client)
One homepage = light `index.html` + a real dark/light switch (inspect dark twins first: keep structural differences as components);
one representative layout per inner page type; portfolio = one chosen layout, database-driven; NO e-commerce (shop pages archived, not routed);
admin must manage all meaningful content of every selected page; admins can create new pages from reusable layouts/section components.

## Exact next task
1. Phase 4 start: copy `1.Orisa HTML Template/assets` -> `public/assets` (keep paths), create `layouts/app.blade.php` + `partials/{header,footer,search}` from `index.html`
   (header ~ lines 96-371), diff `index.html` vs `index-dark.html` to decide the theme-switch approach, and render `/` through Laravel with the existing look.
2. Then build the generic Page + Section system (section type registry in `app/Cms/SectionTypes/*`, partial per type, admin form per type), seed existing homepage content.
3. Then admin CRUD for pages/media/menus/settings, SEO rendering + sitemap/robots/redirects, forms, remaining modules, docs, final testing.
