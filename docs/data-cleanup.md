# Data Cleanup

Phase 8 adds safe cleanup commands:

```bash
php artisan media:cleanup --dry-run
php artisan app:cleanup --dry-run
```

Use dry-run first. Review the output before running destructive cleanup.

Do not remove uploaded media directly from disk unless the related database rows and usage references have been checked.
