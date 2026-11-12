# Server Hardening

## Baseline

- Ubuntu LTS.
- Non-root deployment user.
- SSH key authentication.
- Root SSH login disabled if policy allows.
- Password SSH login disabled if policy allows.
- UFW allows only SSH, HTTP, and HTTPS.
- PostgreSQL is not exposed publicly; only application hosts/users may connect.
- Fail2ban for SSH and abusive Nginx patterns.
- Include `deploy/nginx/cloudflare-real-ip.conf` in Nginx `http {}` only when Cloudflare is enabled and IP ranges are kept current.

## PHP

Recommended production values:

```ini
expose_php=Off
display_errors=Off
log_errors=On
upload_max_filesize=20M
post_max_size=25M
memory_limit=256M
max_execution_time=120
opcache.enable=1
opcache.validate_timestamps=0
```

## Permissions

- `.env` readable only by deployment/app user.
- `storage` and `bootstrap/cache` writable.
- Source code not world-writable.
- Backups outside public web root.
- No PHP execution in upload directories.

## Log Rotation

Use `deploy/logrotate/informatika` as a starting template and adjust paths/users to match the server.
