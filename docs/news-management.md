# News Management

Berita menggunakan tabel `news` dan `news_categories`.

- Konten publik hanya `status=published` dan `published_at <= now()`.
- Draft, scheduled sebelum waktunya, archived, dan soft deleted tidak tampil.
- Slug tersedia untuk ID/EN melalui `slug_id` dan `slug_en`.
- Konten detail disanitasi sebelum dikirim ke React.
- Konten legacy masuk melalui `content:import-legacy` sebagai pending verification.
