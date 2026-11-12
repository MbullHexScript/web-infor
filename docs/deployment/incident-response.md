# Incident Response

## Website Down

- Detection: uptime alert, `/health`, user report.
- Containment: enable maintenance if partial failure, check Nginx/PHP-FPM/DB.
- Recovery: rollback release or restore service.
- Evidence: logs, deployment record, metrics.
- Postmortem: root cause, prevention, owner.

## Database Down

- Check PostgreSQL service, disk, credentials, connection limits, and SINTESA API health.
- Do not run destructive commands.
- Restore only from verified backup with approval.

## SSL or DNS Issue

- Verify certificate chain, Cloudflare mode, DNS target, origin reachability.
- Roll back DNS if needed.

## Security Breach

- Disable affected admin account/session.
- Rotate secrets.
- Preserve logs.
- Review uploads and audit log.
- Notify responsible institutional contacts.

## Backup Failure

- Stop go-live if unresolved.
- Run manual backup.
- Verify checksum.
- Fix destination/permission/space issue.

## Email Failure

- Keep contact form database-first.
- Verify SMTP, SPF, DKIM, DMARC, queue, and logs.
