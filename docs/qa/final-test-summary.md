# Final Test Summary

Date: 2026-07-12

Quality gates to run:

```bash
vendor/bin/pint --test
npm.cmd run format:check
npm.cmd run lint
npm.cmd run typecheck
php artisan test
npm.cmd run build
php artisan curriculum:validate
php artisan translations:audit
php artisan seo:audit
php artisan redirects:audit
php artisan links:audit
php artisan media:audit
php artisan media:find-orphans
php artisan db:audit-performance
php artisan sitemap:validate
php artisan backup:verify
```

Latest final run:

| Gate | Result |
| --- | --- |
| Pint | passed |
| Prettier | passed |
| ESLint | passed |
| TypeScript | passed |
| PHPUnit | 74 passed, 351 assertions |
| Production build | passed |
| Curriculum validation | passed |
| Translation audit | passed with known issues |
| SEO audit | passed |
| Redirect audit | passed |
| Link audit | passed lightweight audit |
| Media audit | passed with 1 UAT orphan |
| Sitemap generation/validation | passed |
| Backup run/verify | passed |
| Dependency audit | failed due Vite/esbuild advisory requiring breaking upgrade |
