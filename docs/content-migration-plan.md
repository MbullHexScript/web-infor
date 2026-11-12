# Content Migration Plan

| URL sumber | Jenis konten | Status verifikasi | Target halaman | Status terjemahan | Status migrasi media | Redirect lama ke baru | Catatan revisi |
| --- | --- | --- | --- | --- | --- | --- | --- |
| https://informatika.fsainsuinbanten.my.id/ | Website lama Prodi | pending | /id | pending | pending | / -> /id | Inventarisasi detail dilakukan pada fase migrasi. |
| https://informatika.fsainsuinbanten.my.id/struktur-organisasi-2/ | Struktur organisasi dan dosen | pending | /id/profil/struktur-pengelola dan /id/profil/dosen | pending | pending | /struktur-organisasi-2/ -> /id/profil/struktur-pengelola | Nama, gelar, dan jabatan harus diverifikasi admin. |
| https://uinbanten.ac.id/ | Referensi universitas | pending | Footer dan link institusi | pending | n/a | n/a | Jangan menyalin klaim yang belum relevan. |
| https://fsainsuinbanten.my.id/ | Referensi fakultas | pending | Footer dan link fakultas | pending | n/a | n/a | Perlu verifikasi struktur fakultas. |
| https://hmps-pink.vercel.app/ | Website HMPS | pending | /id/kemahasiswaan/hmps | pending | n/a | n/a | Tetap website terpisah, hanya ditautkan. |
| storage/app/imports/curriculum/Draf_Kurikulum_OBE_2026_S1_Informatika_UIN_SMH_Banten Rev.1.xlsx | Kurikulum | pending | /id/akademik/kurikulum dan /id/akademik/mata-kuliah | pending | n/a | n/a | Workbook tersedia. Importer dibuat pada fase akademik. |

## Phase 07 Student Life

Commands:

```bash
php artisan student-life:inspect-legacy
php artisan student-life:import-legacy --type=achievements --dry-run
php artisan student-life:import-legacy --type=facilities --dry-run
php artisan student-life:import-legacy --type=alumni --dry-run
php artisan student-life:import-legacy --all --dry-run
php artisan student-life:import-legacy --all
```

Dry-run tidak menulis database. Commit hanya membuat `content_migrations` pending. Data personal mahasiswa/alumni, NIM lengkap, kontak pribadi, dan sertifikat sensitif ditolak dari migrasi otomatis.

## Phase 09 Final Migration Rules

Commands:

```bash
php artisan quality:audit --write-inventory
php artisan translations:audit
php artisan links:audit
php artisan redirects:audit
php artisan media:audit
```

Rules:

- Jangan import artikel demo, placeholder tema lama, statistik lama tanpa bukti, atau berita generik tanpa konteks.
- Jangan import data dosen, akreditasi, mitra, PMB, RPS, atau prestasi sebagai verified tanpa sumber resmi.
- Website lama dipakai sebagai sumber inventaris, bukan sumber kebenaran final.
- Semua URL lama penting harus dipetakan ke URL baru sebelum redirect 301 dibuat.
- Laporan final ada di `docs/content/legacy-migration-final-report.md`.
