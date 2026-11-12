# Phase 03 Academics

Tahap 3 menambahkan modul akademik Kurikulum OBE 2026 berbasis workbook resmi.

## Implementasi

- Migrasi memperkaya tabel akademik dan menambah `curriculum_imports`.
- Importer tersedia melalui `curriculum:inspect`, `curriculum:import --dry-run`, `curriculum:import --update-existing`, dan `curriculum:validate`.
- Laporan import tersimpan di `storage/logs/imports`.
- Seeder akademik mengimpor workbook bila tersedia dan tidak menggagalkan deployment jika file tidak ada.
- Public page tersedia untuk kurikulum, mata kuliah, detail mata kuliah, peminatan, RPS, kalender akademik, jadwal, dan dokumen akademik.
- Admin page tersedia untuk dashboard akademik, import kurikulum, dan upload RPS.

## Hasil Import

- Mata kuliah: 65.
- Total paket mahasiswa: 144 SKS.
- Peminatan: 3.
- Profil lulusan: 4.
- Warning final: 0.
- Error final: 0.

## Perlu Verifikasi Lanjutan

- Nama Inggris mata kuliah.
- Software pembelajaran.
- PDF RPS resmi.
- Dokumen kalender akademik, jadwal kuliah, dan dokumen akademik publik.

## Visual QA

Screenshot disimpan di `docs/qa/academics/` untuk halaman kurikulum, struktur mata kuliah, RPS, dan peminatan pada viewport 360x800, 390x844, 768x1024, 1024x768, 1366x768, 1440x900, dan 1920x1080. Hasil otomatis: tidak ada horizontal overflow, setiap halaman memiliki satu H1, dan tidak ada console error.
