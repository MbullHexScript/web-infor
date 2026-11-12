# Phase 06 Audit - Riset dan Inovasi

Tanggal audit: 2026-07-12

## Baseline Tahap 0-5

- `php artisan migrate:fresh --seed`: lulus sebelum implementasi Tahap 6.
- `vendor/bin/pint --test`: lulus.
- `npm run format:check`: lulus.
- `npm run lint`: lulus.
- `npm run typecheck`: lulus.
- `php artisan test`: 52 tests lulus.
- `npm run build`: lulus.

## Inventaris Sumber

| URL sumber | Jenis konten | Judul | Tahun | Ketua/PJ | Anggota | Luaran | Verifikasi | Media | Tautan eksternal | Terjemahan | Target URL baru | Catatan |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `https://informatika.fsainsuinbanten.my.id/jurnal/` | journal | Rumah jurnal lama | - | - | - | - | pending | unknown | sumber lama | pending | `/id/riset/rumah-jurnal` | Perlu review manual. |
| `https://informatika.fsainsuinbanten.my.id/pengabdian-2/` | community-service | Pengabdian lama | - | - | - | - | pending | unknown | sumber lama | pending | `/id/riset/pengabdian` | Perlu review manual. |
| `https://informatika.fsainsuinbanten.my.id/kerjasama/` | partnership | Kerja sama lama | - | - | - | - | pending | unknown | sumber lama | pending | `/id/riset/kerja-sama` | Perlu review manual. |
| `https://fsainsuinbanten.my.id/` | faculty-reference | Referensi fakultas | - | - | - | - | pending | unknown | fakultas | pending | - | Dipakai hanya sebagai inventaris, bukan klaim otomatis. |
| `https://uinbanten.ac.id/` | university-reference | Referensi universitas | - | - | - | - | pending | unknown | universitas | pending | - | Dipakai hanya sebagai inventaris, bukan klaim otomatis. |

## Data Ditolak

Tautan yang tidak memuat kata kunci riset, jurnal, pengabdian, penelitian, publikasi, kerja sama, atau HKI ditandai `rejected` oleh `research:inspect-legacy`.

## Catatan

- Tidak ada judul penelitian, publikasi, HKI, ISSN, editor, indeksasi, atau OJS yang dikarang.
- NEXUS, DIGIMAS, dan ABDIMATEK disiapkan dengan status `preparing`.
- ISSN, editor, akreditasi, indeksasi, dan OJS URL tetap null sampai tersedia data resmi.
