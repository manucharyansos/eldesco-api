# ELDESCO Production Deployment Guide

## System Requirements
- Ubuntu 22.04 LTS (recommended)
- 2GB RAM minimum (4GB recommended)
- 20GB disk space minimum
- Static IP address
- Domain names: `eldesco.am`, `api.eldesco.am`, `www.eldesco.am`

## Pre-Deployment Checklist
- [ ] DNS records configured (A records pointing to server IP)
- [ ] SSH keys set up for secure access
- [ ] Domain registered and verified
- [ ] SendGrid account created for email
- [ ] Sentry account set up for error tracking
- [ ] Server access via SSH as root

## Deployment Steps

### 1. Connect to Server
```bash
ssh root@YOUR_SERVER_IP
```

### 2. Clone Repositories
```bash
cd /var/www
git clone https://github.com/manucharyansos/eldesco-api.git
git clone https://github.com/manucharyansos/eldesco-client.git

cd eldesco-api
chmod +x setup-production.sh
chmod +x backup.sh
```

### 3. Run Setup Script
```bash
./setup-production.sh
```

This script will:
- Install all dependencies (PHP, MySQL, Redis, Node.js)
- Create database and user
- Install Composer and npm packages
- Run Laravel migrations
- Configure Nginx
- Set up Let's Encrypt SSL
- Configure PM2 for Next.js

### 4. Configure Environment Variables

**For API (.env):**
```bash
cd /var/www/eldesco-api
nano .env
```

Edit:
- `DB_PASSWORD=` — Your MySQL password
- `APP_KEY=` — Run `php artisan key:generate`
- `MAIL_PASSWORD=` — SendGrid API key
- `SENTRY_LARAVEL_DSN=` — Sentry DSN
- `APP_URL=https://api.eldesco.am`

**For Client (.env.production):**
```bash
cd /var/www/eldesco-client
# Already configured, but verify URLs
cat .env.production
```

### 5. Set File Permissions
```bash
sudo chown -R www-data:www-data /var/www/eldesco-api/storage
sudo chown -R www-data:www-data /var/www/eldesco-api/bootstrap/cache
sudo chmod -R 755 /var/www/eldesco-api/storage
sudo chmod -R 755 /var/www/eldesco-api/bootstrap/cache
```

### 6. SSL Renewal Setup
```bash
# Test renewal (dry-run)
sudo certbot renew --dry-run

# Set up automatic renewal (runs twice daily)
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

### 7. Database Backup Setup
```bash
# Make backup script executable
chmod +x /var/www/eldesco-api/backup.sh

# Add to crontab (daily at 2 AM)
sudo crontab -e
# Add: 0 2 * * * /var/www/eldesco-api/backup.sh
```

### 8. Redis Configuration
```bash
# Verify Redis is running
redis-cli ping
# Should return: PONG

# Check Redis status
sudo systemctl status redis-server
```

### 9. Nginx Verification
```bash
# Test Nginx configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx

# Check status
sudo systemctl status nginx
```

### 10. PM2 Process Manager
```bash
# Check running processes
pm2 status

# View logs
pm2 logs eldesco-client

# Restart if needed
pm2 restart all
pm2 save
```

## Post-Deployment Verification

### Check API
```bash
curl -H "Content-Type: application/json" https://api.eldesco.am/api/health
# Should return: {"status":"ok"}
```

### Check Website
```bash
curl https://eldesco.am
# Should return HTML content
```

### Check Logs
```bash
# Laravel logs
tail -f /var/www/eldesco-api/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# PM2 logs
pm2 logs eldesco-client
```

### Test Email
```bash
# In Laravel tinker
cd /var/www/eldesco-api
php artisan tinker

# Send test email
Mail::raw('Test message', function($msg) {
  $msg->to('admin@eldesco.am')->subject('Test');
});
```

## Maintenance

### Daily
- Monitor error logs
- Check disk space: `df -h`
- Verify backups completed: `ls -lh /var/backups/eldesco/`

### Weekly
- Review application logs
- Check Redis memory: `redis-cli info memory`
- Test database backups

### Monthly
- Review Sentry error reports
- Update system packages: `sudo apt update && sudo apt upgrade`
- Check SSL certificate expiration: `certbot certificates`

## Monitoring

### System Resources
```bash
# Check CPU/Memory
top

# Check disk usage
du -sh /var/*

# Check Redis memory
redis-cli info memory
```

### Database
```bash
# MySQL status
sudo systemctl status mysql

# Check database size
mysql -u eldesco_user -p -e "SELECT table_name, ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb FROM information_schema.TABLES WHERE table_schema = 'eldesco_db';"
```

### Application
```bash
# Laravel cache status
php artisan cache:clear

# View queue jobs
php artisan queue:failed

# Clear old sessions
php artisan session:table
```

## Troubleshooting

### Nginx not starting
```bash
sudo nginx -t  # Check syntax
sudo systemctl restart nginx
```

### Database connection failed
```bash
# Check MySQL status
sudo systemctl status mysql

# Verify credentials
mysql -u eldesco_user -p eldesco_db
```

### SSL certificate issues
```bash
# Renew certificate
sudo certbot renew --force-renewal

# Check certificate details
openssl x509 -in /etc/letsencrypt/live/eldesco.am/cert.pem -text -noout
```

### PM2 process crashed
```bash
pm2 restart eldesco-client
pm2 save
```

## Security Hardening

### 1. Firewall
```bash
sudo ufw status
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### 2. SSH Security
```bash
# Edit SSH config
sudo nano /etc/ssh/sshd_config

# Recommended settings:
# PermitRootLogin no
# PasswordAuthentication no
# Port 2222 (optional)

sudo systemctl restart ssh
```

### 3. Fail2Ban (prevent brute force)
```bash
sudo apt install fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### 4. Regular Updates
```bash
# Enable automatic updates
sudo apt install unattended-upgrades
sudo dpkg-reconfigure -plow unattended-upgrades
```

## Scaling Considerations

As traffic grows:
1. **Database** — Add read replicas, implement caching
2. **Caching** — Increase Redis memory
3. **Load Balancing** — Add multiple servers with nginx upstream
4. **CDN** — Use CloudFlare for static assets
5. **Monitoring** — Implement full APM (Application Performance Monitoring)

## Support & Emergency Contacts

- **Sentry Alerts** — configured@sentry.io
- **Backup Status** — /var/log/eldesco-backup.log
- **System Administrator** — admin@eldesco.am

---

**Last Updated:** 2026-09-17
**Maintained By:** ELDESCO Development Team
