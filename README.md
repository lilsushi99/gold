# Orisa CMS (Laravel 12)

Database-driven website + admin dashboard built on the **Orisa v4.1 HTML template**.
Original template files are kept untouched in `1.Orisa HTML Template/`, `2.Orisa Development/`,
`3.Documentation/`, `4.Figma Design/` as reference/archive.

**Status: work in progress** - see `PROJECT_PROGRESS.md` for what is built and what is next,
and `PROJECT_ARCHITECTURE.md` for the design.

## Stack
Laravel 12 (PHP 8.2+), Blade, MySQL 5.7+/MariaDB 10.3+, Bootstrap 5.3 (from the template). No Node/Redis/queue worker in production.

## Requirements
PHP 8.2+ with extensions: mbstring, xml, curl, zip, gd (or imagick), pdo_mysql, fileinfo, intl (recommended); Composer 2.

## Local installation
```bash
composer install
cp .env.example .env        # set DB_* and APP_URL; set APP_ENV=local, APP_DEBUG=true
php artisan key:generate
php artisan migrate --seed  # prints the initial administrator password once
php artisan serve           # http://127.0.0.1:8000  -> admin at /admin
```
No Packagist access? See `tools/offline-composer/README.md`.

### Alternative: import SQL (phpMyAdmin)
Create an empty utf8mb4 database, then import `database/sql/complete_database.sql`.
It creates the same structure and initial data as `migrate --seed` (administrator: see below).

## Initial administrator
Seeded once from `.env` (`ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`). If `ADMIN_PASSWORD` is empty a random
password is generated and printed. The account **must change its password at first login**.
The SQL file contains the *development* administrator `admin@orisa.test` - change it or delete it before going live.

## Tests
HTTP checks live in `tests/manual/` (run with a server up):
`python3 tests/manual/auth_flow.py http://127.0.0.1:8000 admin@orisa.test <initial-password>`
(Run against a freshly seeded DB; it changes the admin password.)
