# Phase 04 Profile Module

Tahap 4 menambahkan modul profil Program Studi Informatika.

## Halaman Publik

- `/id/profil` dan `/en/profile`
- Tentang, Sejarah, Visi/Misi/Tujuan, Sambutan Kaprodi
- Struktur Pengelola
- Dosen dan detail dosen
- Tenaga Kependidikan
- Profil Lulusan
- Akreditasi
- Kerja Sama

## Admin

- `/admin/profile-module`
- `POST /admin/profile-module/lecturers/import-legacy`

## Migrasi Dosen

Command:

```bash
php artisan lecturers:inspect-legacy
php artisan lecturers:import-legacy --dry-run
php artisan lecturers:import-legacy
```

Hasil legacy saat ini: 8 dosen, 8 foto ditemukan, 3 jabatan struktur.

## Catatan Data

- Akreditasi tidak dipublikasikan karena belum ada nomor keputusan dan masa berlaku verified.
- Mitra legacy tampil sebagai `needs_review` untuk review konten.
- Detail pendidikan, tautan akademik, dan publikasi dosen masih perlu pengisian admin.

## Visual QA

Screenshot disimpan di `docs/qa/profile/` untuk halaman profil utama, direktori dosen, struktur pengelola, akreditasi, dan kerja sama pada viewport 360x800, 390x844, 768x1024, 1024x768, 1366x768, 1440x900, dan 1920x1080. Hasil otomatis: tidak ada horizontal overflow, setiap halaman memiliki H1, dan tidak ada console error.
