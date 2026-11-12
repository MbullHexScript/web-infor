# Phase 05 Audit - Modul Informasi

Tanggal audit: 2026-07-12

## Baseline Tahap 0-4

- `php artisan migrate:fresh --seed`: lulus sebelum implementasi Tahap 5.
- `vendor/bin/pint --test`: lulus sebelum implementasi Tahap 5.
- `npm run format:check`: lulus sebelum implementasi Tahap 5.
- `npm run lint`: lulus sebelum implementasi Tahap 5.
- `npm run typecheck`: lulus sebelum implementasi Tahap 5.
- `php artisan test`: 46 tests lulus sebelum implementasi Tahap 5.

## Inventaris Sumber Lama

Sumber utama: `https://informatika.fsainsuinbanten.my.id/`

| URL sumber | Judul konten | Jenis | Tanggal | Kategori | Media | Relevansi | Verifikasi | Terjemahan | Target URL baru | Catatan migrasi |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `https://informatika.fsainsuinbanten.my.id/berita/` | Halaman daftar berita | news | - | berita | terdeteksi di halaman | needs_review | pending | pending | `/id/informasi/berita` | Perlu review manual. |
| `https://informatika.fsainsuinbanten.my.id/pengumuman/` | Halaman pengumuman | announcement | - | pengumuman | unknown | needs_review | pending | pending | `/id/informasi/pengumuman` | Perlu review manual. |
| `https://informatika.fsainsuinbanten.my.id/kegiatan/` | Halaman kegiatan | event | - | kegiatan | unknown | needs_review | pending | pending | `/id/informasi/agenda` | Perlu review manual. |
| `https://informatika.fsainsuinbanten.my.id/kalender-akademik/` | Kalender Akademik | document | - | dokumen | unknown | needs_review | pending | pending | `/id/informasi/unduhan` | Perlu file sumber sebelum publikasi. |
| `https://informatika.fsainsuinbanten.my.id/jadwal-kuliah/` | Jadwal Kuliah | document | - | dokumen | unknown | needs_review | pending | pending | `/id/informasi/unduhan` | Perlu file sumber sebelum publikasi. |
| `https://informatika.fsainsuinbanten.my.id/mbkm/` | MBKM | document | - | dokumen | unknown | needs_review | pending | pending | `/id/informasi/unduhan` | Perlu file sumber sebelum publikasi. |

## Konten Ditolak

| URL sumber | Judul | Alasan penolakan |
| --- | --- | --- |
| `https://informatika.fsainsuinbanten.my.id/2026/04/06/tips-belajar-programming-lebih-cepat-untuk-mahasiswa-informatika/` | Tips Belajar Programming Lebih Cepat untuk Mahasiswa Informatika | Terindikasi artikel demo/generik; tidak dipublikasikan otomatis sebagai berita resmi. |
| `https://informatika.fsainsuinbanten.my.id/2026/04/06/peran-cloud-computing-dalam-transformasi-digital/` | Peran Cloud Computing dalam Transformasi Digital | Terindikasi artikel demo/generik; tidak dipublikasikan otomatis sebagai berita resmi. |

## Catatan

- Tidak ada hotlink media lama yang digunakan.
- Command `content:inspect-legacy` tersedia untuk audit ulang.
- Command `content:import-legacy --dry-run` tidak mengubah database.
- Import non-dry-run hanya membuat catatan `content_migrations` dan `legacy_redirects`; konten tetap pending verification.
