#!/bin/bash

# ELDESCO Automated Backup Script
# Run this daily via crontab: 0 2 * * * /var/www/eldesco-api/backup.sh

BACKUP_DIR="/var/backups/eldesco"
DB_NAME="eldesco_db"
DB_USER="eldesco_user"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=30

# Create backup directory
mkdir -p $BACKUP_DIR

echo "[$(date)] Starting ELDESCO backup..." >> /var/log/eldesco-backup.log

# 1. Database Backup
echo "[$(date)] Backing up MySQL database..." >> /var/log/eldesco-backup.log
mysqldump -u $DB_USER -p $DB_NAME | gzip > $BACKUP_DIR/database_${DATE}.sql.gz

if [ $? -eq 0 ]; then
    echo "[$(date)] ✓ Database backup successful" >> /var/log/eldesco-backup.log
else
    echo "[$(date)] ✗ Database backup failed" >> /var/log/eldesco-backup.log
fi

# 2. Files Backup
echo "[$(date)] Backing up application files..." >> /var/log/eldesco-backup.log
tar -czf $BACKUP_DIR/storage_${DATE}.tar.gz -C /var/www/eldesco-api storage/ public/

if [ $? -eq 0 ]; then
    echo "[$(date)] ✓ Files backup successful" >> /var/log/eldesco-backup.log
else
    echo "[$(date)] ✗ Files backup failed" >> /var/log/eldesco-backup.log
fi

# 3. Clean old backups (keep last 30 days)
echo "[$(date)] Cleaning old backups (keeping last ${RETENTION_DAYS} days)..." >> /var/log/eldesco-backup.log
find $BACKUP_DIR -type f -mtime +$RETENTION_DAYS -delete

# 4. Upload to S3 (optional - requires AWS CLI configured)
if command -v aws &> /dev/null; then
    echo "[$(date)] Uploading to S3..." >> /var/log/eldesco-backup.log
    aws s3 cp $BACKUP_DIR/database_${DATE}.sql.gz s3://eldesco-backups/
    aws s3 cp $BACKUP_DIR/storage_${DATE}.tar.gz s3://eldesco-backups/
    echo "[$(date)] ✓ S3 upload successful" >> /var/log/eldesco-backup.log
fi

# 5. Send notification (optional)
BACKUP_SIZE=$(du -sh $BACKUP_DIR | cut -f1)
echo "[$(date)] Backup completed. Total size: ${BACKUP_SIZE}" >> /var/log/eldesco-backup.log

echo ""
