# PROJECT_ARCHITECTURE.md

Status: **Phase 1 (audit) complete. Phase 2 (Laravel 12 foundation, migrations, seeders, SQL export) complete. Phase 3 partially done (admin auth + shell). See PROJECT_PROGRESS.md.**
See `PROJECT_PROGRESS.md` for the handover state.

## 1. Existing frontend (audit result)

Source: `Orisa v4.1 HTML` (commercial multi-demo template: "Orisa - Creative Agency & Portfolio").

| Folder | Contents |
|---|---|
| `1.Orisa HTML Template/` | Built static site: 74 HTML pages + `assets/` (≈118 MB: imgs 108 MB, css 4.4 MB, fonts 3.9 MB, js 1.4 MB) |
| `2.Orisa Development/` | Gulp/SCSS/Pug-style source (375 html files incl. generated). Not deployed. |
| `3.Documentation/` | Vendor documentation site (15 pages). Not part of the public website. |
| `4.Figma Design/` | Request form text file only. No design files. |

### Stack found
- Bootstrap **5.3.8** (`assets/css/vendors/bootstrap.min.css`), `data-bs-theme="light"` on `<html>`; dark demos use a separate `-dark.html` file.
- jQuery 3.7.1, Swiper, Isotope, Magnific Popup, Odometer, Nice Select, imagesLoaded, Splitting, parallax, carouselTicker, elevateZoom, Matter.js (throwable), custom cursor/cursor-trail, several portfolio slideshow scripts.
- Icons: Font Awesome Pro (local, `font-awesome-pro.css`), local fonts. No CDN dependencies: the only external URLs are outbound links (instagram.com, behance.net, clutch.co, award sites, YouTube/Vimeo video links).
- One compiled stylesheet `assets/css/main.css` (+ map), `sticky-cards.css`.
- No SEO beyond a single `<title>` and `<meta description>` per page; no canonical/OG/Twitter/schema.
- Forms: all `action="#"` (non-functional). Real forms: contact (`contact-1`, `contact-2`, also embedded in `index`/`about` footers as a "sec-4-about-form"), checkout billing/payment (`product-checkout`), plus a search overlay on every page (also `action="#"`). No newsletter backend.

### Page inventory (74 files)
- **Home demos (31):** `index`, `index-2` … `index-15`, each with a `-dark` twin (30) → *these are alternative homepages, not 31 distinct pages.*
- **About (3):** `about-1..3` · **Services (4):** `services-1..3`, `services-details`
- **Portfolio (18):** `portfolio-1..6`, 9 slideshow/gallery layouts (`cinema, curtain, gallery, horizontal, split, stack, vista, zstack`), `portfolio-details-1..6`
- **Blog (5):** `archive-1..4`, `blog-details`
- **Team (2):** `team`, `team-details` · **Other (6):** `pricing`, `faqs`, `contact-1/2`, `404`, `coming-soon`
- **Shop (4):** `product-archive`, `product-details`, `product-cart`, `product-checkout`

Shared structure: search overlay → `<header>` (lines ≈96-371, mega-menu/offcanvas nav) → page `<main>` sections → `<footer>` (two footer variants: `container-2200` on homepages, `footer-fixed-bottom bg-neutral-950 changeless` on inner pages).

## 2. Critical scope decision (needs client confirmation)

The template is a **demo kit**, not a finished website: 15 homepage variants, 6 portfolio list layouts, 3 about/services/contact variants, all containing Orisa demo text and stock images. A real client site uses **one** variant of each.

**Assumption used unless told otherwise** (to be confirmed):
- Home: `index.html` (light) with dark mode switch handled by the existing `color-modes.js`, rather than 30 separate pages.
- One variant each of about / services / contact / portfolio / blog / team / pricing / faqs, plus 404.
- Shop pages (`product-*`) are **out of scope** (no e-commerce/payments requested). They would need an orders/payments module.
- Unused demo variants are kept in the repo under their original folders but are **not routed**.

## 3. Proposed Laravel architecture

- **Laravel 12 (chosen: supports PHP 8.2-8.5; Laravel 13 needs PHP 8.3, Laravel 11 is past security support)** on **PHP 8.2** (check the host's cPanel PHP selector; Laravel 11 needs ≥ 8.2). Blade only, MySQL/MariaDB, no Redis/Queue worker (use `sync` queue, `file`/`database` cache, `database` sessions).
- Laravel project at repo root; original template folders stay untouched. Frontend assets copied (not rewritten) to `public/assets/` so every `assets/...` URL keeps working via `asset()`.
- Public document root = `public/`. cPanel layout: app outside `public_html`, contents of `public/` in `public_html` with adjusted `index.php` paths (documented in `CPANEL_DEPLOYMENT.md`).
- No Node at runtime: template assets are pre-built; no Vite build needed for the public site. Admin uses Bootstrap 5.3 (already vendored) + small vanilla JS.

### Layers
`Models` → `Services` (PageRenderer, SeoService, SitemapService, MediaService, RedirectService, SettingsService, RevisionService) → thin `Controllers` (Public / Admin namespaces) → Blade (`layouts`, `partials`, `sections/<type>.blade.php`, `admin/*`). Validation in Form Requests; authorization via Gates/Policies and a `permission` middleware.

### Section-based rendering
Each page = ordered `page_sections` rows. Each row has a `type` (e.g. `hero`, `services_grid`, `stats`, `testimonials`, `faq`, `cta`, `logo_ticker`, `portfolio_grid`), a JSON `content` validated by a per-type schema class (`App\Cms\SectionTypes\*`), an `is_visible` flag and `position`. Each type owns **(a)** a Blade partial copied 1:1 from the original markup with `{{ }}` placeholders, **(b)** a schema (fields, repeaters, validation), **(c)** an admin form generated from the schema. This keeps original markup/classes intact and prevents layout-breaking edits (no free-form HTML except in explicit rich-text fields, which are sanitized).

## 4. Proposed database (MySQL 5.7+/MariaDB 10.3+ compatible)

`users`, `roles`, `permissions`, `permission_role`, `role_user` (or `role_id` on users), `password_reset_tokens`, `sessions`,
`pages` (title, slug, template, status, publish_at, parent_id, featured_media_id),
`page_sections` (page_id FK, type, position, is_visible, content JSON),
`page_revisions` (page_id FK, snapshot JSON, created_by),
`seo_metadata` (polymorphic seoable; title, description, keywords, canonical, robots, og_*, twitter_*, schema JSON),
`media` (path, disk, mime, size, width, height, alt, title, description, uploaded_by) + `media_usages` (prevents deleting in-use files),
`menus`, `menu_items` (nested via parent_id, target, is_active),
`posts`, `categories`, `tags`, `post_tag`,
`testimonials`, `faqs`, `team_members`, `services`, `portfolio_items` (these mirror the template's repeating content),
`contact_submissions` (status: new/read/archived; ip; user_agent),
`settings` (key/value/group), `redirects` (source unique, destination, code, is_active, hits),
`activity_logs` (user_id, action, subject_type/id, properties JSON without secrets), `jobs`-free.

Install paths: `php artisan migrate --seed` **and** `database/sql/complete_database.sql`, generated from the migrated database via `mysqldump` and verified by importing into a clean database.

## 5. Admin modules
Dashboard · Pages · Page sections (per-type editors, reorder, hide, duplicate) · Posts/Categories/Tags · Media library · Menus · Services/Portfolio/Team/Testimonials/FAQs · Contact submissions · SEO manager (global, per-page, previews, sitemap status, robots.txt, redirects) · Settings (general, contact, social, footer, analytics/verification) · Users & roles · Activity log · Backup/DB info (read-only export guidance; no web-based shell).
Roles: Super Admin, Content Admin, SEO Admin (configurable permission sets).

## 6. Integration strategy
1. Copy `assets/` to `public/assets/`.
2. Extract header/footer/search overlay once into `partials/` (menu + footer data from DB).
3. Convert each in-scope page into a Blade view built from section partials; verify pixel parity against the original (screenshots at desktop/tablet/mobile).
4. Seed the DB from the template's real content so the admin is pre-populated.
5. Replace `action="#"` forms with CSRF-protected POST routes (honeypot + throttle, optional configurable SMTP via settings/.env).
6. Redirect `*.html` legacy URLs to clean slugs (301).

## 7. Hosting and deployment constraints
- Repo is ≈ 275 MB mostly demo images; the production bundle should include only the assets actually used (optional prune step, documented).
- Shared hosting: no long-running processes; cron optional (scheduled publishing via `schedule:run` every minute, with a request-time fallback check if cron is unavailable).
- Detailed procedure in `CPANEL_DEPLOYMENT.md` (to be written in Phase 7).


## 8. Implementation notes (as built)
- Admin assets live in `public/cms-assets/` (a `public/admin/` folder would shadow the `/admin` routes on Apache and the PHP dev server).
- Schema: 30 tables created by 9 migrations (+ Laravel cache table); `Setting::get()` is cached and invalidated on save.
- Roles/permissions are data (`roles`, `permissions`, `permission_role`); `User::hasPermission()` (super-admin bypass); route middleware `permission:slug`.
- Redirect lookups use a `source_hash` (sha256) unique index so long URLs stay indexable on MySQL.
