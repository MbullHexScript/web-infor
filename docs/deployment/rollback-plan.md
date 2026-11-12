# Rollback Plan

## Application Rollback

Use:

```bash
scripts/rollback-production.sh <release-directory-name>
```

This switches the `current` symlink, clears/caches Laravel, restarts queue workers, reloads PHP-FPM if permitted, and checks `/health`.

## Database Rollback

Database rollback is not automatic.

Only restore a database backup when:

- A migration is not backward-compatible.
- Data corruption happened.
- The impact is understood and approved.

Before database restore:

1. Put site in maintenance mode.
2. Backup current broken state for evidence.
3. Restore known-good backup.
4. Run smoke test.
5. Document downtime and impact.
