# Admin Guide

## Akademik

Menu akademik tersedia di `/admin/academic`. Import kurikulum tersedia di `/admin/academic/curriculum-imports` dengan alur inspect/dry-run sebelum commit. RPS diunggah dari `/admin/rps` dengan validasi PDF, checksum, versi, status public, dan status current.

## Profil

Dashboard profil tersedia di `/admin/profile-module`. Admin dapat menjalankan import dosen legacy dan melihat status kelengkapan halaman, dosen, struktur, akreditasi, dan mitra.

Admin masuk melalui `/admin/login`. Akun dibuat dari environment oleh seeder. Setelah login, admin dapat membuka `/admin/security/two-factor` untuk mengaktifkan 2FA.

CRUD konten lengkap belum tersedia pada Fase 1.

## Homepage

Admin dapat membuka `/admin/homepage` untuk:

- Mengaktifkan atau menonaktifkan section homepage.
- Mengubah urutan section melalui angka `sort_order`.
- Mengubah judul ID/EN section.
- Mengubah quick links.
- Mengaktifkan statistik setelah data diverifikasi.
- Preview homepage melalui tombol ke `/id`.

Pengelolaan detail hero, dosen, berita, fasilitas, dan media library akan diperluas pada fase admin lengkap.

## Kemahasiswaan, PMB, Fasilitas, Kontak

Tahap 7 menambahkan dashboard:

- `/admin/student-life`
- `/admin/facilities`
- `/admin/admissions`
- `/admin/contact`

Admin dapat memantau prestasi, beasiswa, kompetisi, alumni, fasilitas, jalur PMB, FAQ PMB, dan migrasi legacy. Inbox kontak mendukung status read, replied, archived, spam, soft delete, dan export CSV aman tanpa IP/user-agent.

CRUD editor penuh untuk setiap entitas masih menjadi pekerjaan lanjutan.

## Operasional Admin

Tahap 8 menambahkan panel operasional:

- `/admin/media` untuk upload, metadata, replacement, dan audit media.
- `/admin/seo` untuk SEO settings dan sitemap.
- `/admin/redirects` untuk redirect legacy yang tervalidasi.
- `/admin/settings` untuk pengaturan situs, navigasi, dan tautan sosial.
- `/admin/audit-log` untuk melihat aktivitas admin.
- `/admin/account` untuk password dan recovery codes.
- `/admin/operations` untuk backup, health summary, dan maintenance mode.
- `/admin/quality` untuk baseline audit, verifikasi konten, dan coverage RPS.

Panel ini memperkuat operasional dan keamanan. CRUD editor penuh untuk semua jenis konten tetap dikerjakan bertahap.

## UAT dan Go-Live

Admin UAT memakai checklist `docs/uat/uat-checklist.md`. Catat issue di `docs/uat/uat-issues.csv` dan ringkas di `docs/uat/uat-summary.md`. Go-live hanya boleh dilakukan setelah approval lengkap.
