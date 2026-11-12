# Deployment

Target produksi: Ubuntu Server, Nginx, PHP-FPM, PostgreSQL, SSL, Cloudflare, queue worker, scheduler, dan Git deployment.

## Environment

- Local
- Staging: `staging-informatika.uinbanten.ac.id`
- Production: `informatika.uinbanten.ac.id`

## Checklist Awal

1. Install PHP 8.2+ dengan `pdo_pgsql`, Composer, Node.js, PostgreSQL, dan Nginx.
2. Clone repository.
3. Buat `.env` production.
4. Isi `APP_KEY`, database, mail, cache/session, dan `ADMIN_*`.
5. Jalankan `composer install --no-dev --optimize-autoloader`.
6. Jalankan `npm ci` dan `npm run build`.
7. Jalankan `php artisan migrate --force`.
8. Jalankan `php artisan storage:link`.
9. Konfigurasikan Nginx ke folder `public`.
10. Aktifkan SSL.
11. Jalankan queue worker dan scheduler.
12. Aktifkan backup.

## Phase 10 Runbook

Template tersedia:

- `.env.staging.example`
- `.env.production.example`
- `deploy/nginx/informatika.uinbanten.ac.id.conf`
- `deploy/nginx/cloudflare-real-ip.conf`
- `deploy/systemd/informatika-queue.service`
- `deploy/cron/informatika-scheduler`
- `deploy/logrotate/informatika`
- `scripts/deploy-staging.sh`
- `scripts/deploy-production.sh`
- `scripts/rollback-production.sh`
- `scripts/smoke-test-production.sh`

Dokumen go-live:

- `docs/deployment/pre-deployment-audit.md`
- `docs/deployment/staging-setup.md`
- `docs/deployment/go-live-checklist.md`
- `docs/deployment/go-live-approval.md`
- `docs/deployment/final-deployment-test-report.md`

## Health Check

Laravel menyediakan endpoint `/up` untuk health check dasar dan `/health` untuk status minimal database, cache, dan storage tanpa secret.

## Backup dan Rollback

Detail backup, restore, dan rollback ada di `docs/backup-restore.md`.

Sebelum production:

```bash
php artisan sitemap:generate
php artisan sitemap:validate
php artisan backup:run
php artisan backup:verify
php artisan media:audit
php artisan redirects:audit
php artisan seo:audit
```

Konfigurasi backup terenkripsi ke lokasi luar server sebelum menerima trafik produksi.
