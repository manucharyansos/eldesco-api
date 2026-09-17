# GitHub Actions Secrets Setup Guide

## Required Secrets for CI/CD

Go to: GitHub Repository → Settings → Secrets and variables → Actions → New repository secret

### Deployment Secrets

**DEPLOY_HOST**
- Value: Your server IP address or domain (e.g., `eldesco.am`)
- Type: Plain text

**DEPLOY_USER**
- Value: SSH user (e.g., `eldesco` or `root`)
- Type: Plain text

**DEPLOY_PATH**
- API Value: `/var/www/eldesco-api`
- Client Value: `/var/www/eldesco-client`
- Type: Plain text

**DEPLOY_KEY**
- Generate SSH key on server:
  ```bash
  ssh-keygen -t ed25519 -f github_deploy_key -C "github-actions"
  cat github_deploy_key
  ```
- Add public key to server:
  ```bash
  cat github_deploy_key.pub >> ~/.ssh/authorized_keys
  chmod 600 ~/.ssh/authorized_keys
  ```
- Value: Contents of `github_deploy_key` (private key)
- Type: Secret

### Notification Secrets

**SLACK_WEBHOOK**
- Create webhook at: https://api.slack.com/messaging/webhooks
- Value: Full webhook URL
- Type: Secret

**SENTRY_AUTH_TOKEN** (optional)
- Get from: https://sentry.io/settings/auth-tokens/
- Value: Your Sentry auth token
- Type: Secret

### Environment Variables

Add to `.env` on production server:

```bash
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eldesco_db
DB_USERNAME=eldesco_user
DB_PASSWORD=YOUR_STRONG_PASSWORD

# Cache
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.xxxxxxxxxxxxx

# URLs
APP_URL=https://api.eldesco.am
SANCTUM_STATEFUL_DOMAINS=eldesco.am,www.eldesco.am

# Monitoring
SENTRY_LARAVEL_DSN=https://xxxxx@sentry.io/xxxxx
```

## Testing Secrets Locally

```bash
# Create local .env.local with same values
cp .env.example .env.local

# Run tests
composer test

# Build
npm run build
```

## SSH Key Setup

### On Your Local Machine
```bash
ssh-keygen -t ed25519 -f ~/.ssh/eldesco_deploy -C "eldesco-github"
```

### Add Public Key to Server
```bash
ssh-copy-id -i ~/.ssh/eldesco_deploy.pub eldesco@your-server-ip
```

### Add Private Key to GitHub
1. Go to Repository → Settings → Secrets
2. Create `DEPLOY_KEY` secret
3. Paste contents of `~/.ssh/eldesco_deploy` (private key)

### Test Connection
```bash
ssh -i ~/.ssh/eldesco_deploy eldesco@your-server-ip "whoami"
```

## Troubleshooting CI/CD

### Workflow not triggering
- Check branch name is exactly `main`
- Verify `.github/workflows/` files are on main branch
- Check Actions tab for errors

### Deployment failed
```bash
# SSH into server and check:
cd /var/www/eldesco-api
tail -f storage/logs/laravel.log

pm2 logs eldesco-client
```

### Permission denied on deploy
```bash
# Verify SSH key has correct permissions
chmod 600 ~/.ssh/eldesco_deploy
chmod 700 ~/.ssh

# Test SSH connection
ssh -v -i ~/.ssh/eldesco_deploy eldesco@server
```

### Build fails
- Check Node version: `node --version` should be 18.x
- Check disk space: `df -h`
- Clear cache: `npm cache clean --force`

## Monitoring Deployments

### View Workflow Runs
1. Go to Actions tab
2. Click on workflow
3. View real-time logs

### Slack Notifications
- Deployment started
- Build success/failure
- Deployment status

### Sentry Monitoring
- Real-time error tracking
- Performance monitoring
- Release tracking

## Security Best Practices

✅ Do:
- Rotate deployment keys quarterly
- Use strong database passwords
- Enable 2FA on GitHub
- Review deployment logs weekly
- Keep secrets in GitHub Actions only

❌ Don't:
- Commit `.env` files
- Share SSH keys
- Use same key for multiple servers
- Leave default passwords
- Expose secrets in logs

## Scaling Deployments

When ready for multiple servers:

1. Create separate secrets for each environment
   - `PROD_DEPLOY_HOST`
   - `STAGING_DEPLOY_HOST`

2. Create environment-specific workflows
   - `.github/workflows/deploy-staging.yml`
   - `.github/workflows/deploy-production.yml`

3. Use GitHub Environments
   - Settings → Environments → New environment
   - Add protection rules (require approval, etc.)

4. Example matrix deployment:
```yaml
strategy:
  matrix:
    server: [prod, staging]
env:
  DEPLOY_HOST: ${{ secrets[format('{0}_DEPLOY_HOST', matrix.server)] }}
```

---

**Last Updated:** 2026-09-17
**Questions?** Check GitHub Actions documentation at https://docs.github.com/en/actions
