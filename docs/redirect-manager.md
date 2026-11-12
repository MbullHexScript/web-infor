# Redirect Manager

Redirect management is available at `/admin/redirects`.

Rules:

- Old and new paths cannot be identical.
- Unsafe schemes such as `javascript:`, `data:`, and `vbscript:` are rejected.
- Redirects into `/admin` are rejected.
- Duplicate source paths, chains, and loops are guarded.
- Runtime redirects increment hit counts.

Audit command:

```bash
php artisan redirects:audit
```
