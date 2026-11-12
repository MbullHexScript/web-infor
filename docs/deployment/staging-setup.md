# Staging Setup

Target: `https://staging-informatika.uinbanten.ac.id`

## Rules

- `APP_ENV=staging`
- `APP_DEBUG=false`
- Staging uses noindex and should disallow crawling.
- Use a separate database, storage, and backup destination.
- Use mail sandbox or restricted SMTP.
- Do not use production admin password.
- Seed UAT data with `php artisan db:seed --class=UatSeeder`.
- Do not enable production analytics.

## Steps

1. Provision server, database, and app user.
2. Copy `.env.staging.example` to shared `.env`.
3. Fill secrets on the server only.
4. Run `scripts/deploy-staging.sh`.
5. Run `php artisan quality:audit --write-inventory`.
6. Run UAT with `docs/uat/uat-checklist.md`.

## Noindex

Staging should be protected by Nginx basic auth or IP allowlist when possible. If public, set a robots policy that disallows all crawlers.

This repository includes `deploy/staging-robots.txt`, and `scripts/deploy-staging.sh` copies it to `public/robots.txt` during staging deployment.
