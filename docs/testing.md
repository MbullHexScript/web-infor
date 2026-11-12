# Testing

## Phase 03

Test akademik mencakup inspect workbook, dry-run tanpa perubahan data, commit idempotent, halaman kurikulum dan mata kuliah, detail mata kuliah, detail peminatan, serta RPS draft yang tidak tampil di publik.

## Phase 04

Test profil mencakup halaman profil ID/EN, tentang, sejarah, visi/misi, sambutan, direktori dan detail dosen, struktur pengelola, staff empty state, profil lulusan, akreditasi fallback, kerja sama, serta import dosen dry-run/idempotent.

Quality gate fase:

- Laravel Pint.
- Prettier check.
- ESLint.
- TypeScript type checking.
- PHPUnit feature tests.
- Production build.

Coverage akan bertambah mengikuti fase fitur.

## Fase 2

Ditambahkan `tests/Feature/HomepageTest.php` untuk homepage ID/EN, section active, announcement expiration, statistics visibility, lecturer verification, journal active state, homepage cache, cache invalidation, dan quick link external state.

Visual QA homepage disimpan di `docs/qa/homepage/`.

## Phase 07

`tests/Feature/StudentLifeModuleTest.php` mencakup route publik, privacy guard prestasi, filter, beasiswa, kompetisi, fasilitas, alumni consent, form kontak, mail failure safety, admin inbox, dan migrasi dry-run/idempotent.

Quality gate terakhir:

- `vendor/bin/pint --test`
- `npm.cmd run format:check`
- `npm.cmd run lint`
- `npm.cmd run typecheck`
- `php artisan test`
- `npm.cmd run build`

## Phase 08

`tests/Feature/AdminOperationsSecurityTest.php` mencakup proteksi route admin, upload media valid, upload berbahaya, deletion guard untuk media terpakai, redirect safety, runtime redirect, security headers, health endpoint, sitemap, backup, cleanup, audit command, 2FA enforcement, dan recovery code redaction.

Command yang aman untuk automated test dijalankan di suite. `mail:test` tidak dijalankan otomatis karena bergantung pada transport email environment.

## Phase 09

`tests/Feature/QualityAuditTest.php` mencakup route `/admin/quality`, `/admin/quality/content-verification`, `/admin/quality/rps-coverage`, serta command `translations:audit`, `db:audit-performance`, dan `quality:audit`.

Final quality gate juga mencakup `quality:audit --write-inventory` untuk memperbarui inventaris konten.

## Phase 10

Deployment readiness terakhir:

- `php artisan test` passed, 74 tests and 351 assertions.
- `npm.cmd run lint` passed.
- `npm.cmd run typecheck` passed.
- `npm.cmd run build` passed.
- `php artisan route:cache` passed.
- `npm.cmd audit --audit-level=high` failed due Vite/esbuild advisory requiring a breaking upgrade decision.
