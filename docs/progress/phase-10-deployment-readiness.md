# Phase 10 Deployment Readiness

Date: 2026-07-12

## Implemented

- `.env.staging.example`
- `.env.production.example`
- Staging noindex support through `X-Robots-Tag` and `deploy/staging-robots.txt`
- HSTS preload opt-in through `HSTS_PRELOAD`
- Nginx production template
- Cloudflare real-IP template
- systemd queue worker template
- scheduler cron template
- logrotate template
- staging deploy script
- production deploy script
- rollback script
- production smoke test script
- UAT issue tracking
- deployment, rollback, DNS, Cloudflare, backup, monitoring, incident response, handover, and maintenance docs

## Local Verification

- `vendor/bin/pint --test`: passed.
- `npm.cmd run format:check`: passed.
- `npm.cmd run lint`: passed.
- `npm.cmd run typecheck`: passed.
- `npm.cmd run build`: passed.
- `php artisan test`: passed, 74 tests and 351 assertions.
- `php artisan route:cache`: passed.
- `php artisan quality:audit --write-inventory`: passed.
- `php artisan curriculum:validate`: passed.
- `php artisan sitemap:generate`: passed.
- `php artisan sitemap:validate`: passed.
- `php artisan backup:run`: passed.
- `php artisan backup:verify`: passed.

## Blocked External Work

- Staging server access.
- DNS access.
- SSL certificate access.
- Cloudflare account access.
- SMTP production credentials.
- Off-server backup destination.
- Restore test environment.
- UAT approval.
- Production deploy approval.

## No-Go Items For Production

- UAT has not started.
- RPS official files are missing.
- Lecturer and institutional data still require official verification.
- Dependency audit reports Vite/esbuild advisories requiring a breaking upgrade decision.
- Bash script syntax could not be checked locally because `bash` is not available in PATH.
- Production server, queue, scheduler, monitoring, DNS, SSL, and Cloudflare are not configured from this workspace.
