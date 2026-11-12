# RPS Management

RPS dikelola dari `/admin/rps`.

## Aturan

- File wajib PDF.
- Ukuran maksimal default 20 MB.
- Checksum SHA-256 disimpan.
- Satu mata kuliah dapat memiliki banyak versi.
- Saat versi baru ditandai current, versi current sebelumnya untuk mata kuliah dan kurikulum yang sama otomatis dinonaktifkan.
- RPS publik harus `is_public=true`, `is_current=true`, dan `status=published`.
