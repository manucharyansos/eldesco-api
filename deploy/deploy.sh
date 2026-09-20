#!/usr/bin/env bash
# Update a running server:  ./deploy/deploy.sh
# Safe to run repeatedly. Never touches .env, the database contents or uploaded files.
set -euo pipefail
cd "$(dirname "$0")/.."

echo "==> Pulling latest code"
git pull --ff-only

echo "==> Installing PHP dependencies"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Migrating database"
php artisan migrate --force

echo "==> Linking public storage"
php artisan storage:link --force || true

echo "==> Rebuilding caches"
php artisan optimize:clear
php artisan optimize

if command -v systemctl >/dev/null 2>&1; then
  sudo systemctl reload php8.3-fpm 2>/dev/null || sudo systemctl reload php-fpm 2>/dev/null || true
fi

echo "Done."
