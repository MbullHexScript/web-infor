# Lecturer Migration

Migrasi dosen memakai inventory lokal dari halaman lama `struktur-organisasi-2`.

```bash
php artisan lecturers:inspect-legacy
php artisan lecturers:import-legacy --dry-run
php artisan lecturers:import-legacy
```

Dry-run tidak mengubah database. Commit bersifat idempotent berdasarkan slug. Foto diunduh hanya saat import/seed non-testing dan disimpan lokal agar halaman publik tidak hotlink ke website lama.
