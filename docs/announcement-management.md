# Announcement Management

Pengumuman menggunakan tabel `announcements`.

- Pengumuman aktif memiliki `status=published`, `starts_at <= now()`, dan `expires_at` kosong atau masih di masa depan.
- Priority: `normal`, `important`, `urgent`.
- Urgent dibedakan dengan ikon dan label, bukan warna saja.
- Pengumuman expired tidak tampil pada daftar aktif tetapi masih dapat dibuka lewat detail/arsip.
