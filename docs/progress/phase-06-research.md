# Phase 06 - Riset dan Inovasi

Tahap ini menambahkan modul publik dan admin dasar untuk:

- penelitian dosen,
- pengabdian kepada masyarakat,
- publikasi,
- produk digital,
- HKI,
- kelompok keahlian,
- rumah jurnal,
- kerja sama riset.

## Route Publik

- `/id/riset`, `/en/research`
- `/id/riset/penelitian`, `/en/research/projects`
- `/id/riset/pengabdian`, `/en/research/community-service`
- `/id/riset/publikasi`, `/en/research/publications`
- `/id/riset/produk`, `/en/research/products`
- `/id/riset/hki`, `/en/research/intellectual-property`
- `/id/riset/kelompok-keahlian`, `/en/research/expertise-groups`
- `/id/riset/rumah-jurnal`, `/en/research/journals`
- `/id/riset/kerja-sama`, `/en/research/partnerships`

## Jurnal

Seed final:

- NEXUS
- DIGIMAS
- ABDIMATEK

Status awal `preparing`. Data ISSN, editor, indeksasi, akreditasi, bulan terbit, dan OJS tidak diisi sampai tersedia sumber resmi.

## Command Migrasi

```bash
php artisan research:inspect-legacy
php artisan research:import-legacy --type=research --dry-run
php artisan research:import-legacy --type=community-service --dry-run
php artisan research:import-legacy --type=publications --dry-run
php artisan research:import-legacy --all --dry-run
php artisan research:import-legacy --all
```

## Pending

- CRUD editor admin penuh untuk setiap entitas riset.
- Verifikasi data penelitian, pengabdian, publikasi, produk, HKI, kelompok keahlian, dan mitra riset.
- OJS dan metadata jurnal setelah tersedia resmi.
