# Phase 05 - Modul Informasi

Tahap ini menambahkan modul informasi publik untuk berita, pengumuman, agenda, galeri, pusat unduhan, FAQ, pencarian global, dan migrasi konten lama.

## Halaman Publik

- `/id/informasi`, `/en/information`
- `/id/informasi/berita`, `/en/information/news`
- `/id/informasi/pengumuman`, `/en/information/announcements`
- `/id/informasi/agenda`, `/en/information/events`
- `/id/informasi/galeri`, `/en/information/gallery`
- `/id/informasi/unduhan`, `/en/information/downloads`
- `/id/informasi/faq`, `/en/information/faq`
- `/id/pencarian`, `/en/search`

## Admin

- `/admin/information`
- `/admin/news`
- `/admin/announcements`
- `/admin/events`
- `/admin/gallery`
- `/admin/documents`
- `/admin/faqs`

Dashboard admin menampilkan metrik, daftar konten, dan tombol inspeksi/import legacy terkontrol.

## Migrasi Legacy

- `php artisan content:inspect-legacy`
- `php artisan content:import-legacy --type=news --dry-run`
- `php artisan content:import-legacy --type=gallery --dry-run`
- `php artisan content:import-legacy --type=documents --dry-run`
- `php artisan content:import-legacy --all --dry-run`
- `php artisan content:import-legacy --all`

Hasil import disimpan sebagai pending verification dan tidak langsung published.

## Catatan Pending

- CRUD admin penuh masih perlu diperluas dari dashboard ringkas menjadi editor form lengkap.
- Media lama belum dipindahkan karena tidak ada konten yang lolos publikasi otomatis.
- Sitemap khusus informasi dapat ditambahkan pada tahap SEO lanjutan.
