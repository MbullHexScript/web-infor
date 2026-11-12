# Website Program Studi Informatika

Website resmi Program Studi Informatika, Fakultas Sains dan Teknologi, UIN Sultan Maulana Hasanuddin Banten.

Tagline: Empowering Digital Innovation with Islamic Values.

## Modul Akademik

Tahap 3 menambahkan Kurikulum OBE 2026 berbasis workbook resmi.

```bash
php artisan curriculum:inspect
php artisan curriculum:import --dry-run
php artisan curriculum:import --update-existing
php artisan curriculum:validate
```

Halaman publik utama tersedia di `/id/akademik/kurikulum`, `/id/akademik/mata-kuliah`, `/id/akademik/peminatan`, dan `/id/akademik/rps`.

## Modul Profil

Tahap 4 menambahkan halaman profil utama, tentang, sejarah, visi/misi, sambutan Kaprodi, struktur pengelola, dosen, tenaga kependidikan, profil lulusan, akreditasi, dan kerja sama.

```bash
php artisan lecturers:inspect-legacy
php artisan lecturers:import-legacy --dry-run
php artisan lecturers:import-legacy
```

## Modul Informasi

Tahap 5 menambahkan berita, pengumuman, agenda, galeri, pusat unduhan, FAQ, pencarian global, dan migrasi konten lama terkontrol.

```bash
php artisan content:inspect-legacy
php artisan content:import-legacy --all --dry-run
php artisan content:import-legacy --all
```

Halaman publik utama tersedia di `/id/informasi`, `/id/informasi/berita`, `/id/informasi/pengumuman`, `/id/informasi/agenda`, `/id/informasi/galeri`, `/id/informasi/unduhan`, `/id/informasi/faq`, dan `/id/pencarian`.

## Modul Riset dan Inovasi

Tahap 6 menambahkan penelitian, pengabdian, publikasi, produk digital, HKI, kelompok keahlian, rumah jurnal, dan kerja sama riset.

```bash
php artisan research:inspect-legacy
php artisan research:import-legacy --all --dry-run
php artisan research:import-legacy --all
```

Rumah jurnal menampilkan NEXUS, DIGIMAS, dan ABDIMATEK dengan status awal `preparing`.

## Modul Kemahasiswaan, PMB, Fasilitas, dan Kontak

Tahap 7 menambahkan kemahasiswaan, kehidupan mahasiswa, prestasi, beasiswa, kompetisi, MBKM/magang, alumni, HMPS, fasilitas, PMB, dan kontak.

```bash
php artisan student-life:inspect-legacy
php artisan student-life:import-legacy --all --dry-run
php artisan student-life:import-legacy --all
```

Halaman utama tersedia di `/id/kemahasiswaan`, `/id/fasilitas`, `/id/pmb`, dan `/id/kontak`. Form kontak menyimpan pesan lebih dulu dan aman terhadap kegagalan email.

## Operasional Admin dan Security

Tahap 8 menambahkan dashboard operasional, media library, SEO manager, redirect manager, site settings, audit log, backup smoke test, health check, maintenance mode, dan security hardening.

```bash
php artisan media:audit
php artisan seo:audit
php artisan sitemap:generate
php artisan redirects:audit
php artisan backup:run
php artisan backup:verify
php artisan app:cleanup --dry-run
```

Panel admin utama tersedia di `/admin`, `/admin/media`, `/admin/seo`, `/admin/redirects`, `/admin/settings`, `/admin/audit-log`, `/admin/account`, dan `/admin/operations`.

## Quality dan UAT

Tahap 9 menambahkan audit non-destruktif dan dashboard quality.

```bash
php artisan translations:audit
php artisan db:audit-performance
php artisan quality:audit --write-inventory
php artisan db:seed --class=UatSeeder
```

Dashboard tersedia di `/admin/quality`, `/admin/quality/content-verification`, dan `/admin/quality/rps-coverage`.

`UatSeeder` hanya boleh dipakai di local/staging/testing dan ditolak di production.

## Deployment dan Go-Live

Tahap 10 menambahkan template deployment:

```bash
scripts/deploy-staging.sh
scripts/deploy-production.sh
scripts/rollback-production.sh
scripts/smoke-test-production.sh
```

Template server tersedia di `deploy/`, termasuk Nginx, Cloudflare real IP, systemd queue worker, scheduler cron, dan logrotate.

Production belum boleh go-live sebelum UAT disetujui, RPS resmi tersedia, data institusional terverifikasi, backup off-server aktif, restore test lulus, DNS/SSL/Cloudflare siap, dan dependency audit diputuskan.

## Stack

- Laravel 12
- Inertia.js
- React
- TypeScript
- Tailwind CSS
- PostgreSQL

## Requirements

- PHP 8.2+
- Composer 2
- Node.js 20+
- PostgreSQL 15+

## Instalasi Lokal

1. Salin `.env.example` ke `.env`.
2. Isi konfigurasi database.
3. Isi `ADMIN_NAME`, `ADMIN_EMAIL`, dan `ADMIN_PASSWORD`.
4. Jalankan `composer install`.
5. Jalankan `npm.cmd install` di Windows atau `npm install` di shell lain.
6. Jalankan `php artisan key:generate`.
7. Jalankan `php artisan migrate --seed`.
8. Jalankan `php artisan storage:link`.

## Integrasi SINTESA

SINTESA adalah sumber data akademik otoritatif. Website menyimpan salinan baca pada database PostgreSQL sendiri agar halaman publik tetap tersedia ketika API sumber sedang mengalami gangguan. Konfigurasikan `SINTESA_API_URL` dan `SINTESA_API_TOKEN`, lalu jalankan:

```bash
php artisan sintesa:sync --dry-run
php artisan sintesa:sync
```

Scheduler menjalankan sinkronisasi setiap enam jam. Gunakan `--only=catalog` atau `--only=lecturers` untuk membatasi ruang sinkronisasi. Deskripsi, foto, dan konten editorial website tidak dihapus oleh sinkronisasi.

## Development

Jalankan backend:

```bash
php artisan serve
```

Jalankan frontend:

```bash
npm.cmd run dev
```

## Build

```bash
npm.cmd run build
```

## Test dan Quality Gate

```bash
vendor/bin/pint
npm.cmd run format:check
npm.cmd run lint
npm.cmd run typecheck
php artisan test
```

## Login Admin

Admin dibuat oleh seeder dari environment:

- `ADMIN_NAME`
- `ADMIN_EMAIL`
- `ADMIN_PASSWORD`

Tidak ada registrasi publik.

## Homepage Publik

- Bahasa Indonesia: `/id`
- Bahasa Inggris: `/en`
- Admin homepage settings: `/admin/homepage`

Homepage menggunakan cache per bahasa. Cache otomatis dibersihkan saat admin memperbarui section, quick links, atau statistics.

## Import Kurikulum

Importer Excel Kurikulum OBE 2026 belum dibuat pada Fase 1. Pekerjaan ini masuk Fase 3.

## Storage

File publik akan disajikan melalui storage link Laravel. Media library lengkap dikerjakan pada fase admin.

## Troubleshooting

- Jika `npm` gagal di PowerShell karena execution policy, gunakan `npm.cmd`.
- Jika seeder gagal, pastikan semua variabel `ADMIN_*` sudah diisi.
- Jika Composer memilih Laravel terbaru yang meminta PHP 8.3, gunakan lock file proyek ini.
