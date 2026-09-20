# ELDESCO API

REST API backend for ELDESCO LLC website built with Laravel 11 and PostgreSQL.

## Requirements

- PHP 8.2+
- Composer
- PostgreSQL 12+
- Node.js 18+ (for frontend)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/manucharyansos/eldesco-api.git
cd eldesco-api
```

2. Install dependencies:
```bash
composer install
```

3. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env`:
```
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=eldesco_db
DB_USERNAME=eldesco_user
DB_PASSWORD=your_password
```

5. Run migrations:
```bash
php artisan migrate
```

6. (Optional) Seed initial data:
```bash
php artisan db:seed
```

## Running the Server

Development:
```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api`

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

- `POST /api/auth/register` - Register new admin user
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

## Production deployment (https://api.eldesco.am)

The website (https://eldesco.am) is a separate Next.js app; this API only serves JSON and uploaded images.

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env            # then edit: APP_ENV=production, APP_DEBUG=false,
php artisan key:generate        # APP_URL=https://api.eldesco.am, DB_*, ELDESCO_ADMIN_PASSWORD,
                                # CORS_ALLOWED_ORIGINS=https://eldesco.am,https://www.eldesco.am
php artisan migrate --force
php artisan storage:link        # uploaded images are served from https://api.eldesco.am/storage/...
php artisan db:seed --class=PresentationContentSeeder --force   # first deploy only, see below
php artisan config:cache && php artisan route:cache
```

`PresentationContentSeeder` loads the pages, services, menus and photos from the company presentation.
Site settings are only created when missing, but **pages, services and menus it manages are reset**
to the presentation defaults - run it once, then edit everything from the admin panel.

Nginx (PHP-FPM) example:

```nginx
server {
    server_name api.eldesco.am;
    root /var/www/eldesco-api/public;
    index index.php;
    client_max_body_size 12M;          # image uploads (limit is 10 MB per file)

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }
    location ~ /\.(?!well-known) { deny all; }
}
```

Make sure `storage/` and `bootstrap/cache/` are writable by the PHP user, and issue the TLS certificate
(for example `certbot --nginx -d api.eldesco.am`).

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
