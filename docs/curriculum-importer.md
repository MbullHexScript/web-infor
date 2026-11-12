# Curriculum Importer

Importer membaca workbook Kurikulum OBE 2026 dengan adapter Python `openpyxl`.

## Konfigurasi

- `CURRICULUM_IMPORT_PATH`: path workbook.
- `CURRICULUM_PYTHON_BINARY`: path Python binary.

## Command

```bash
php artisan curriculum:inspect
php artisan curriculum:import --dry-run
php artisan curriculum:import --update-existing
php artisan curriculum:validate
```

## Sheet Didukung

- `Daftar MK - Flat`
- `Ringkasan Semester MK`
- `Peminatan Mahasiswa`
- `Profil Lulusan`
- `Prasyarat`

## Laporan

Setiap dry-run dan commit menghasilkan JSON dan Markdown di `storage/logs/imports` berisi checksum, mode, sheet, rows, created, updated, skipped, warning, error, dan rekap SKS.
