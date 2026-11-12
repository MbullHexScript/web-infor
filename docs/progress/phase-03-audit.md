# Phase 03 Audit

Tanggal audit: 2026-07-12.

## Baseline Tahap 0-2

- Migration fresh + seeder: lulus.
- Pint: lulus.
- Prettier check: lulus.
- ESLint: lulus.
- TypeScript check: lulus.
- PHPUnit: lulus.
- Production build: lulus.
- Homepage dan admin tetap stabil.

## File Kurikulum

- Lokasi: `storage/app/imports/curriculum/Draf_Kurikulum_OBE_2026_S1_Informatika_UIN_SMH_Banten Rev.1.xlsx`
- SHA-256: `1b19e7526e6df1ce0dc89e47150159f066b9659cda04540bef9053a1cf1ef3f5`
- Jumlah sheet: 42.

## Sheet Sumber

Sheet utama publik:

- `Daftar MK - Flat`
- `Ringkasan Semester MK`
- `Peminatan Mahasiswa`
- `Profil Lulusan`
- `Prasyarat`

Sheet lain dibaca untuk audit nama, tetapi matriks OBE teknis tidak ditampilkan ke publik.

## Kolom Utama

`Daftar MK - Flat` memiliki kolom: Semester, Kode Mata Kuliah, Nama Mata Kuliah, Kelompok Mata Kuliah, Status, SKS, Bentuk Pembelajaran, Prasyarat, CPL Didukung, Peminatan, Penciri Prodi, Catatan Keterkaitan.

## Rekap

| Semester | MK | SKS Wajib | SKS Pilihan Ditawarkan | Paket Mahasiswa |
| --- | ---: | ---: | ---: | ---: |
| 1 | 9 | 20 | 0 | 20 |
| 2 | 9 | 20 | 0 | 20 |
| 3 | 8 | 22 | 0 | 22 |
| 4 | 8 | 22 | 0 | 22 |
| 5 | 11 | 18 | 9 | 21 |
| 6 | 11 | 18 | 9 | 21 |
| 7 | 7 | 9 | 9 | 12 |
| 8 | 2 | 6 | 0 | 6 |

- Total paket mahasiswa: 144 SKS.
- Total SKS ditawarkan: 162 SKS.
- Mata kuliah: 65.
- Peminatan: 3.
- Profil lulusan: 4.
- Baris prasyarat: 52.
- Duplikat kode: tidak ada.
- Duplikat nama: tidak ada.
- Warning final: 0.
- Error final: 0.

## Catatan

- Nama Inggris mata kuliah belum tersedia di workbook.
- Software pembelajaran belum tersedia eksplisit di workbook.
- RPS PDF belum tersedia di workbook dan perlu upload admin.
- PhpSpreadsheet tidak dapat dipasang di PHP lokal karena `ext-zip` dan `ext-gd` belum tersedia; importer memakai `openpyxl` melalui Python yang dapat dikonfigurasi.
