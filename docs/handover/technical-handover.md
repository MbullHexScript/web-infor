# Technical Handover

## Architecture

Laravel 12, Inertia.js, React, TypeScript, Tailwind CSS, PostgreSQL, Nginx, PHP-FPM, queue worker, scheduler, and optional Cloudflare. SINTESA is the authoritative academic-data source and is accessed through a scoped API client.

## Environments

- Local: developer machine.
- Staging: `staging-informatika.uinbanten.ac.id`.
- Production: `informatika.uinbanten.ac.id`.

## Deployment

Scripts:

- `scripts/deploy-staging.sh`
- `scripts/deploy-production.sh`
- `scripts/rollback-production.sh`
- `scripts/smoke-test-production.sh`

Production layout:

- `/var/www/informatika/releases/<release>`
- `/var/www/informatika/shared`
- `/var/www/informatika/current -> release`

## Operations

- Queue: `php artisan queue:work` managed by Supervisor/systemd.
- Scheduler: cron entry running `php artisan schedule:run`.
- Backup: `php artisan backup:run` and `php artisan backup:verify`.
- Quality: `php artisan quality:audit`.
- Logs: Laravel, Nginx, queue, deployment, backup.

## Secrets

Secrets live only in server `.env`, not repository.

## Troubleshooting

Start with `/health`, Laravel logs, Nginx logs, PHP-FPM status, DB connectivity, queue failed jobs, and deployment record.
