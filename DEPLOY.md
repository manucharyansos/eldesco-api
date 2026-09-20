# ELDESCO API - server deployment (https://api.eldesco.am)

Tested layout: Ubuntu 22.04 / 24.04, nginx, PHP 8.3-FPM, MySQL 8 (or MariaDB 10.6+).
The website (https://eldesco.am) is a separate app - see `eldesco-client/DEPLOY.md`.

## 1. Server packages

```bash
sudo apt update
sudo apt install -y nginx git unzip mysql-server \
  php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd php8.3-intl php8.3-bcmath
# Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

Raise the upload limits (PHP's 2 MB default rejects normal photos):

```bash
sudo cp deploy/php-uploads.ini /etc/php/8.3/fpm/conf.d/99-eldesco.ini   # after step 3
sudo systemctl reload php8.3-fpm
```

## 2. Database

```bash
sudo mysql <<'SQL'
CREATE DATABASE eldesco CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'eldesco'@'localhost' IDENTIFIED BY 'CHANGE_ME';
GRANT ALL PRIVILEGES ON eldesco.* TO 'eldesco'@'localhost';
SQL
```

## 3. Code

```bash
sudo mkdir -p /var/www && sudo chown $USER: /var/www
cd /var/www
git clone https://github.com/manucharyansos/eldesco-api.git
cd eldesco-api
sudo cp deploy/php-uploads.ini /etc/php/8.3/fpm/conf.d/99-eldesco.ini && sudo systemctl reload php8.3-fpm

cp .env.production.example .env
nano .env                                  # DB_PASSWORD, ELDESCO_ADMIN_PASSWORD, domains
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan db:seed --force      # FIRST DEPLOY ONLY: admin user + team + all site content (see note)

sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwX storage bootstrap/cache
php artisan optimize
```

> **Note:** seeding also loads the presentation content (`PresentationContentSeeder`) and resets the pages,
> services and menus it manages to the presentation defaults. Run it on the first deploy only. Later edits are
> made in the admin panel and survive `deploy/deploy.sh`. Seeding twice creates no duplicates, but overwrites
> your edits of those pages, services and menus.

The seeder refuses to create the admin user in production while `ELDESCO_ADMIN_PASSWORD` is still the default value.

## 4. nginx + HTTPS

```bash
sudo cp deploy/nginx-api.conf /etc/nginx/sites-available/api.eldesco.am
sudo ln -s /etc/nginx/sites-available/api.eldesco.am /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx

# DNS: an A record  api.eldesco.am -> server IP must exist first
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d api.eldesco.am
```

## 5. Verify

```bash
curl -s https://api.eldesco.am/up                               # 200 = app is alive
curl -s "https://api.eldesco.am/api/site?lang=en" | head -c 300  # company settings + menus
curl -s "https://api.eldesco.am/api/pages/home?lang=hy" | head -c 300
```

Log in to the admin panel at https://eldesco.am/admin with `ELDESCO_ADMIN_EMAIL` / `ELDESCO_ADMIN_PASSWORD`.

## 6. Updating later

```bash
cd /var/www/eldesco-api && ./deploy/deploy.sh
```

## 7. Backups (do this!)

Everything that matters is the database and `storage/app/public` (uploaded images).

```bash
# /etc/cron.daily/eldesco-backup   (chmod +x)
#!/bin/sh
mkdir -p /var/backups/eldesco
mysqldump --single-transaction eldesco | gzip > /var/backups/eldesco/db-$(date +%F).sql.gz
tar -czf /var/backups/eldesco/uploads-$(date +%F).tar.gz -C /var/www/eldesco-api/storage/app public
find /var/backups/eldesco -mtime +14 -delete
```

## Troubleshooting

| Symptom | Cause / fix |
|---|---|
| Browser console: CORS error in the admin | `CORS_ALLOWED_ORIGINS` must contain the exact site origin (`https://eldesco.am`); run `php artisan config:cache` after editing `.env` |
| Uploaded images 404 | `php artisan storage:link` was not run, or nginx has no `/storage/` location |
| Upload fails with 413 | nginx `client_max_body_size` / PHP `upload_max_filesize` too small (steps 1 and 4) |
| 500 error | `storage/logs/laravel.log`; usually permissions on `storage/` and `bootstrap/cache/` |
| Login says "Too many attempts" | login is limited to 10 tries per minute per IP; wait a minute |

## Local development

```bash
composer install
composer setup     # copies .env, generates key, creates SQLite DB, migrates + seeds, links storage
composer dev       # http://127.0.0.1:8000
```

Then start the website (`npm run dev` in `eldesco-client`, http://localhost:3000). Local admin login:
`admin@eldesco.am` with the password from `.env` (`ELDESCO_ADMIN_PASSWORD`).
