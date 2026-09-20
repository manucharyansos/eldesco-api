# ELDESCO API

REST API backend for ELDESCO LLC website built with Laravel.

## Requirements

- PHP 8.2+ (8.3 recommended) with the usual Laravel extensions
- Composer
- Local development: nothing else (SQLite). Production: MySQL 8 / MariaDB 10.6+

## Quick start (local)

```bash
git clone https://github.com/manucharyansos/eldesco-api.git
cd eldesco-api
composer install
composer setup     # .env, app key, SQLite database, migrations + seed, storage link
composer dev       # API on http://127.0.0.1:8000/api
```

Admin login: `admin@eldesco.am` and the `ELDESCO_ADMIN_PASSWORD` value from `.env`.
Start the website next (`npm run dev` in `eldesco-client`).

**Production:** see [DEPLOY.md](DEPLOY.md) (nginx, PHP-FPM, MySQL, HTTPS, backups, updates).

## API Endpoints

### Public Endpoints

- `GET /api/services` - Get all services
- `GET /api/services/{id}` - Get service by ID
- `GET /api/projects` - Get all projects
- `GET /api/projects/{id}` - Get project by ID
- `GET /api/team` - Get team members
- `GET /api/team/{id}` - Get team member by ID
- `GET /api/news` - Get news/blog posts (paginated)
- `GET /api/news/{slug}` - Get news by slug
- `GET /api/gallery` - Get gallery images
- `GET /api/gallery/categories` - Get gallery categories

Query Parameters:
- `lang` - Language code (hy, en, ru) - default: en
- `page` - Page number for paginated endpoints
- `limit` - Items per page
- `featured` - Filter projects by featured status (true/false)
- `category` - Filter by category

### Authentication

- `POST /api/auth/login` - Login (returns Sanctum token)
- `POST /api/auth/logout` - Logout (requires token)
- `GET /api/auth/me` - Get current user (requires token)
- `POST /api/auth/refresh` - Refresh token (requires token)

### Admin Endpoints (require authentication + admin role)

#### Services Management
- `POST /api/services` - Create service
- `PUT /api/services/{id}` - Update service
- `DELETE /api/services/{id}` - Delete service

#### Projects Management
- `POST /api/projects` - Create project
- `PUT /api/projects/{id}` - Update project
- `DELETE /api/projects/{id}` - Delete project

#### Team Management
- `POST /api/team` - Add team member
- `PUT /api/team/{id}` - Update team member
- `DELETE /api/team/{id}` - Delete team member

#### News Management
- `POST /api/news` - Create news post
- `PUT /api/news/{id}` - Update news post
- `DELETE /api/news/{id}` - Delete news post

#### Gallery Management
- `POST /api/gallery` - Add gallery image
- `PUT /api/gallery/{id}` - Update gallery image
- `DELETE /api/gallery/{id}` - Delete gallery image

## Example Requests

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@eldesco.am","password":"password123"}'
```

Response:
```json
{
  "message": "Login successful",
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "email": "admin@eldesco.am",
    "name": "Admin User",
    "role": "admin"
  }
}
```

### Get Services (with language)
```bash
curl http://localhost:8000/api/services?lang=hy
```

### Create Service (as admin)
```bash
curl -X POST http://localhost:8000/api/services \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title_hy": "Ցածր լարման համակարգեր",
    "title_en": "Low Voltage Systems",
    "description_hy": "Description in Armenian",
    "icon": "bolt"
  }'
```

### Site content (used by the website)

- `GET /api/site?lang=hy|en|ru` - company settings, interface labels and menus (localized)
- `GET /api/pages/{slug}?lang=` - CMS page with its sections

### Admin: site settings, menus, media

- `GET /api/admin/site` - all settings + menus (all languages)
- `PUT /api/admin/site/settings` - `{ "settings": { "contact.phone": "+374..." } }`
- `PUT /api/admin/site/navigation` - `{ "menus": { "header": [...], "footer": [...] } }`
- `GET /api/admin/media`, `POST /api/admin/media`, `DELETE /api/admin/media/{id}`

## Production deployment

See [DEPLOY.md](DEPLOY.md) for the full step-by-step guide for https://api.eldesco.am.

## Structure

- `app/Models/` - Eloquent models
- `app/Http/Controllers/Api/` - API controllers
- `database/migrations/` - Database schemas
- `database/seeders/` - Database seeders
- `routes/api.php` - API routes
- `config/` - Configuration files

## Security

- Uses Laravel Sanctum for API token authentication
- Password hashing with bcrypt
- CORS configured for frontend domain
- SQL injection protection via Eloquent ORM
- CSRF protection on sensitive endpoints

## License

ELDESCO LLC © 2024. All rights reserved.
