# ELDESCO API

Laravel 11 backend for the ELDESCO corporate website and admin-managed CMS.

## What this backend controls

- Public website pages and SEO metadata
- Reorderable page sections
- Armenian / English / Russian text stored in CMS JSON fields
- Section images and gallery media
- Header, footer, navigation, contact and brand settings
- Existing projects, services, news, team and gallery CRUD
- Sanctum token authentication for the admin panel

## Requirements

- PHP 8.2+
- Composer 2
- PostgreSQL, MySQL or SQLite

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
# configure DB_* in .env
php artisan migrate
php artisan db:seed
php artisan db:seed --class=CmsSeeder
php artisan storage:link
php artisan serve
```

The API runs at `http://localhost:8000/api` by default.

> Change the seeded admin password before production. The legacy DatabaseSeeder currently creates `admin@eldesco.am` with a development password.

## CMS endpoints

Public:

- `GET /api/pages`
- `GET /api/pages/{slug}`
- `GET /api/site-settings`

Authenticated admin:

- `GET|POST /api/admin/pages`
- `GET|PUT|DELETE /api/admin/pages/{page}` / page slug lookup for GET
- `POST /api/admin/pages/{page}/sections`
- `PUT|DELETE /api/admin/pages/{page}/sections/{section}`
- `POST /api/admin/pages/{page}/sections/reorder`
- `GET /api/admin/site-settings`
- `PUT /api/admin/site-settings/{key}`
- `POST /api/admin/media`

## Content seeded from the company presentation

`CmsSeeder` creates the public structure for:

- Home
- About ELDESCO
- Low- and medium-voltage power infrastructure
- Industrial production infrastructure
- Refrigeration and cooling
- LED display systems
- Full-cycle sheet metal processing
- Contact

It also creates global navigation and contact settings. The initial content follows the supplied ELDESCO company presentation, while every text and media field can then be changed from the admin CMS.

## Production notes

Use a real `APP_KEY`, disable debug, configure `APP_URL`, set the frontend origin in `CORS_ALLOWED_ORIGINS`, configure your production database, and make `storage` plus `bootstrap/cache` writable. Run migrations, seed the CMS once on a fresh installation, and create the public storage symlink.
