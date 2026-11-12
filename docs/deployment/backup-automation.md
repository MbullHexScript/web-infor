# Backup Automation

Recommended schedule:

- Database backup daily.
- Media backup weekly or incremental.
- Full backup weekly.
- Monthly archive when policy requires it.

Rules:

- Encrypt off-server backups.
- Store outside the public web root.
- Verify checksum after backup.
- Alert on failure.
- Test restore regularly in non-production.

Example cron:

```cron
15 1 * * * deploy cd /var/www/informatika/current && php artisan backup:run && php artisan backup:verify >> /var/log/informatika-backup.log 2>&1
```

Production backup destination must be configured by the server owner.
