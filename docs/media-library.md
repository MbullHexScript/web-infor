# Media Library

Admin media tools are available at `/admin/media`.

Supported controls:

- Upload image, PDF, and approved document types.
- Reject executable/script/SVG uploads.
- Validate real image content and PDF signatures.
- Store checksum, dimensions, alt text, caption, credit, optimization status, and replacement history.
- Prevent deleting media that is referenced by `media_usages`.
- Audit and clean orphan media through Artisan commands.

Commands:

```bash
php artisan media:audit
php artisan media:find-orphans
php artisan media:cleanup --dry-run
```

Only run cleanup without `--dry-run` after reviewing the orphan list.
