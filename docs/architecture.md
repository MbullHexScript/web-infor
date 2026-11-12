# Architecture

## Keputusan Fase 0-1

Website Program Studi Informatika dibangun sebagai aplikasi tunggal Laravel dengan Inertia.js, React, TypeScript, Tailwind CSS, dan PostgreSQL. Database dipisahkan dari SINTESA dan SAKTI. Data akademik otoritatif disinkronkan dari SINTESA melalui API dengan scope terbatas.

Versi fondasi:

- PHP 8.2.12.
- Laravel Framework 12.70.1.
- Inertia Laravel 2.x.
- React 18.
- TypeScript 5.
- Tailwind CSS 3.

Laravel 13 project template tidak dipakai karena membutuhkan PHP 8.3. Laravel 12 dipilih karena stabil, kompatibel dengan PHP 8.2, dan tidak diblokir advisory Composer pada proses instalasi.

## Modul Utama

- Public site: route bahasa `/id` dan `/en`, layout publik, navbar, footer, dan halaman awal.
- Admin: session authentication Laravel, satu admin dari environment, dashboard awal, dan 2FA TOTP.
- Content foundation: site settings, quick links, statistics, journals, legacy redirects, content migrations, dan kerangka tabel domain utama.
- Security: CSRF bawaan Laravel, escaping React, validation request Breeze, rate limit login bawaan, security headers dasar, dan tidak ada registrasi publik.

## Struktur Frontend

Komponen React ditempatkan di `resources/js/Components`, layout di `resources/js/Layouts`, halaman di `resources/js/Pages`, utilitas di `resources/js/Lib`, dan type global di `resources/js/types`.

Design tokens didefinisikan sebagai CSS variables di `resources/css/app.css` agar light/dark mode dapat konsisten dan dapat diperluas.

## Batasan Fase Ini

- Tabel domain fase 1 masih berupa kerangka umum. Kolom lengkap per fitur akan diperdalam pada fase masing-masing.
- Importer kurikulum, media library, CRUD lengkap, sitemap, SEO metadata dinamis, dan migrasi konten lama belum dikerjakan.
