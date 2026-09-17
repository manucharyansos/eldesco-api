#!/bin/bash

# ELDESCO Production Setup Script
# Run on fresh server with Ubuntu 22.04 LTS

set -e

echo "=== ELDESCO PRODUCTION SETUP ==="

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# 1. System Updates
echo -e "${BLUE}1. System Updates${NC}"
sudo apt update
sudo apt upgrade -y

# 2. Install Dependencies
echo -e "${BLUE}2. Installing Dependencies${NC}"
sudo apt install -y curl wget git zip unzip
sudo apt install -y nginx
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-redis php8.2-mbstring php8.2-xml php8.2-curl php8.2-bcmath php8.2-dev
sudo apt install -y mysql-server
sudo apt install -y redis-server
sudo apt install -y certbot python3-certbot-nginx

# 3. Create Application User
echo -e "${BLUE}3. Creating Application User${NC}"
sudo useradd -m -s /bin/bash eldesco || true
sudo mkdir -p /var/www/eldesco-api
sudo mkdir -p /var/www/eldesco-client
sudo chown -R eldesco:eldesco /var/www/eldesco-*

# 4. Composer Installation
echo -e "${BLUE}4. Installing Composer${NC}"
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

# 5. Node.js Installation
echo -e "${BLUE}5. Installing Node.js 18${NC}"
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# 6. MySQL Setup
echo -e "${BLUE}6. Setting up MySQL Database${NC}"
sudo mysql -e "CREATE DATABASE IF NOT EXISTS eldesco_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'eldesco_user'@'localhost' IDENTIFIED BY 'CHANGE_THIS_PASSWORD';"
sudo mysql -e "GRANT ALL PRIVILEGES ON eldesco_db.* TO 'eldesco_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# 7. Redis Configuration
echo -e "${BLUE}7. Configuring Redis${NC}"
sudo systemctl enable redis-server
sudo systemctl start redis-server

# 8. Laravel API Setup
echo -e "${BLUE}8. Setting up Laravel API${NC}"
cd /var/www/eldesco-api
composer install --no-dev --optimize-autoloader

# Create .env from .env.example
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force

# Storage symlink
php artisan storage:link

# Set permissions
sudo chown -R www-data:www-data /var/www/eldesco-api/storage
sudo chmod -R 755 /var/www/eldesco-api/storage

# 9. Next.js Client Setup
echo -e "${BLUE}9. Setting up Next.js Client${NC}"
cd /var/www/eldesco-client
npm install
npm run build

# 10. Nginx Configuration
echo -e "${BLUE}10. Configuring Nginx${NC}"
sudo tee /etc/nginx/sites-available/eldesco-api > /dev/null <<EOF
server {
    listen 80;
    server_name api.eldesco.am;
    root /var/www/eldesco-api/public;
    index index.php;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

sudo tee /etc/nginx/sites-available/eldesco-web > /dev/null <<EOF
server {
    listen 80;
    server_name eldesco.am www.eldesco.am;
    root /var/www/eldesco-client/.next/standalone;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade \$http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host \$host;
        proxy_cache_bypass \$http_upgrade;
    }

    location /_next/static/ {
        alias /var/www/eldesco-client/.next/static/;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
EOF

# Enable sites
sudo ln -sf /etc/nginx/sites-available/eldesco-api /etc/nginx/sites-enabled/ || true
sudo ln -sf /etc/nginx/sites-available/eldesco-web /etc/nginx/sites-enabled/ || true
sudo rm -f /etc/nginx/sites-enabled/default

# Test and restart
sudo nginx -t
sudo systemctl restart nginx

# 11. SSL Certificates (Let's Encrypt)
echo -e "${BLUE}11. Setting up SSL Certificates${NC}"
sudo certbot certonly --nginx -d eldesco.am -d www.eldesco.am -d api.eldesco.am --non-interactive --agree-tos -m admin@eldesco.am || true

# 12. PM2 for Next.js
echo -e "${BLUE}12. Setting up PM2${NC}"
sudo npm install -g pm2
pm2 start npm --name "eldesco-client" -- start --cwd /var/www/eldesco-client
pm2 save
sudo env PATH=$PATH:/usr/bin /usr/local/lib/node_modules/pm2/bin/pm2 startup systemd -u eldesco --hp /home/eldesco

# 13. Cron Jobs
echo -e "${BLUE}13. Setting up Cron Jobs${NC}"
(crontab -u eldesco -l 2>/dev/null || true; echo "* * * * * cd /var/www/eldesco-api && php artisan schedule:run >> /dev/null 2>&1") | crontab -u eldesco -

# 14. Firewall (UFW)
echo -e "${BLUE}14. Configuring Firewall${NC}"
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw --force enable || true

echo -e "${GREEN}✅ ELDESCO PRODUCTION SETUP COMPLETE!${NC}"
echo -e "${BLUE}Next steps:${NC}"
echo "1. Update .env files with real values"
echo "2. Configure SendGrid API key for email"
echo "3. Configure Sentry for error monitoring"
echo "4. Set up automated backups"
echo "5. Configure CDN for static assets"
echo ""
echo "API: https://api.eldesco.am"
echo "Web: https://eldesco.am"
