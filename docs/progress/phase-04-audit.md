# Phase 04 Audit

Tanggal audit: 2026-07-12.

## Baseline

- Migration fresh + seeder: lulus dengan `ADMIN_NAME`, `ADMIN_EMAIL`, dan `ADMIN_PASSWORD`.
- Pint: lulus.
- Prettier check: lulus.
- ESLint: lulus.
- TypeScript check: lulus.
- PHPUnit Tahap 0-3: lulus sebelum implementasi.
- Production build: lulus sebelum implementasi.

## Sumber Legacy

- Homepage lama: `https://informatika.fsainsuinbanten.my.id/`
- Struktur organisasi/dosen: `https://informatika.fsainsuinbanten.my.id/struktur-organisasi-2/`
- Sejarah: `https://informatika.fsainsuinbanten.my.id/sejarah-informatika/`
- Visi dan misi: `https://informatika.fsainsuinbanten.my.id/visi-dan-misi/`
- Kerja sama: `https://informatika.fsainsuinbanten.my.id/kerjasama/`

## Dosen Ditemukan

| Nama | Gelar | Jabatan legacy | Foto |
| --- | --- | --- | --- |
| Ahmad Tabrani | M.T.I | Ketua Program Studi Informatika | ada |
| M. Iman Wahyudi | S.Pd.I, M.Kom | Sekretaris Program Studi Informatika | ada |
| Birru Muqdamien | M.Kom | Gugus Kendali Mutu | ada |
| Dr. Aan Ansori | S.Kom., M.M., M.Kom | belum ada | ada |
| Reza Syafrizal | M.Kom. | belum ada | ada |
| Wawan Setiawan | S.Kom, M.Kom | belum ada | ada |
| Ibnu Mas'ud | S.Kom, M.Kom. | belum ada | ada |
| Dr. Isak Iskandar | M.Pd | belum ada | ada |

Foto legacy diunduh lokal pada seed/import non-testing ke `storage/app/public/lecturers/legacy`.

## Konten yang Dimigrasikan

- Sejarah: izin operasional berdasarkan Keputusan Menteri Nomor 400/E/O/2023 tanggal 2 Mei 2023.
- Visi/misi: digunakan sebagai draft `needs_review`.
- Sambutan Kaprodi: menggunakan narasi Tahap 4.
- Kerja sama: 6 mitra awal dari halaman lama sebagai `needs_review`.

## Data Hilang atau Meragukan

- Email dosen, NIDN, pendidikan, bidang keahlian, Google Scholar, SINTA, ORCID, Scopus, publikasi, dan mata kuliah dosen belum tersedia dari sumber yang cukup kuat.
- Akreditasi “Baik” ditemukan di website lama, tetapi nomor keputusan dan masa berlaku belum diverifikasi, sehingga tidak dipublikasikan.
- Data tenaga kependidikan belum tersedia.

## Status Verifikasi

Data dosen dan struktur hasil migrasi legacy ditandai `needs_review`. Di lingkungan non-production data ini tampil untuk review; di production controller hanya menampilkan data `verified`.
