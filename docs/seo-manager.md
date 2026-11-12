# SEO Manager

SEO management is available at `/admin/seo`.

Phase 8 supports:

- Global SEO settings.
- Metadata audit.
- Sitemap generation.
- Sitemap validation.
- Admin noindex headers.
- Public robots rules with sitemap reference.

Commands:

```bash
php artisan seo:audit
php artisan sitemap:generate
php artisan sitemap:validate
```

The generated files are written to `public/sitemap.xml` and `public/sitemap-pages.xml`.
