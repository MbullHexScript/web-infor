# Laporan Fase 0-1

## Ringkasan Pekerjaan

Fondasi aplikasi Laravel + Inertia + React + TypeScript + Tailwind telah dibuat. Aplikasi memiliki route publik bahasa ID/EN, layout publik, layout admin, auth admin tunggal tanpa registrasi publik, 2FA TOTP, design tokens, dark mode tanpa flicker, security headers dasar, migration inti, dan seeder awal.

## Aset Ditemukan

Workspace awal kosong saat audit pertama. Pada pembaruan berikutnya, logo UIN, logo Informatika, dan file Kurikulum OBE 2026 diberikan lalu disalin ke proyek.

Lokasi aset:

- `public/assets/brand/logo-uin-smh-banten-512.webp`
- `public/assets/brand/logo-informatika-512.webp`
- `storage/app/imports/curriculum/Draf_Kurikulum_OBE_2026_S1_Informatika_UIN_SMH_Banten Rev.1.xlsx`

## File Utama Dibuat

- `TODO.md`
- `CHANGELOG.md`
- `docs/architecture.md`
- `docs/content-migration-plan.md`
- `docs/progress/phase-00-01.md`
- `app/Http/Middleware/SecurityHeaders.php`
- `app/Http/Middleware/EnsureTwoFactorIsVerified.php`
- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Http/Controllers/Admin/TwoFactorController.php`
- `resources/js/Components/Layout/*`
- `resources/js/Pages/Public/Home.tsx`
- `resources/js/Pages/Admin/Dashboard.tsx`
- `resources/js/Pages/Admin/TwoFactor.tsx`
- `resources/js/Pages/Auth/TwoFactorChallenge.tsx`

## Migration Dibuat

- `2026_07_12_000001_create_foundation_content_tables.php`
- `2026_07_12_000002_create_core_domain_tables.php`
- Update users table dasar untuk kolom 2FA.

## Komponen Dibuat

- `LogoMark`
- `NavbarBrand`
- `ThemeToggle`
- `PublicLayout`

## Route Dibuat

- `/` redirect ke `/id`
- `/{locale}` untuk `/id` dan `/en`
- `/admin/login`
- `/admin/two-factor`
- `/admin`
- `/admin/security/two-factor`

## Test yang Dijalankan

- `vendor\bin\pint`
- `npm.cmd run format`
- `npm.cmd run format:check`
- `npm.cmd run lint`
- `npm.cmd run typecheck`
- `npm.cmd run build`
- `php artisan migrate:fresh --seed`
- `php artisan test`
- `composer audit`
- `npm.cmd audit --audit-level=high`

## Hasil Test

- Pint: passed.
- Prettier check: passed.
- ESLint: passed.
- TypeScript: passed.
- Production build: passed.
- Migration dan seeder: passed dengan admin env sementara untuk verifikasi lokal.
- PHPUnit: 25 passed, 55 assertions.
- Composer audit: no security vulnerability advisories found.
- npm audit: masih menemukan vulnerability transitive pada Vite/esbuild; fix yang tersedia memerlukan breaking upgrade, sehingga ditunda dan dicatat di known limitations.

## Masalah yang Ditemukan

- Git sistem tidak tersedia di PATH saat audit awal; repository kemudian berhasil diinisialisasi memakai Git bundled Codex dengan branch `main`.
- `npm.ps1` diblokir oleh execution policy, sehingga perintah npm dijalankan memakai `npm.cmd`.
- Template Laravel terbaru membutuhkan PHP 8.3, sedangkan lingkungan lokal memakai PHP 8.2.12.
- `npm audit` melaporkan 2 vulnerability transitive.

## Keputusan Teknis

- Memakai Laravel 12.63.0 karena kompatibel dengan PHP 8.2 dan stabil.
- Admin memakai session auth Laravel, bukan API terpisah.
- 2FA memakai TOTP ringan melalui Google2FA.
- Registrasi publik tidak diroute.
- Data yang belum resmi disimpan sebagai placeholder dengan `verification_status = pending`.

## Pekerjaan Fase Berikutnya

Fase 2 melengkapi homepage naratif, animasi ringan, section lengkap, data dinamis, dan QA responsive.

## Cara Menjalankan

1. Salin `.env.example` ke `.env`.
2. Isi database PostgreSQL dan `ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`.
3. Jalankan `composer install`.
4. Jalankan `npm.cmd install`.
5. Jalankan `php artisan key:generate`.
6. Jalankan `php artisan migrate --seed`.
7. Jalankan `npm.cmd run dev` dan `php artisan serve`.
