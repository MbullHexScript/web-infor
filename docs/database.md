# Database

## Phase 03 Academic Tables

Tabel akademik inti dari fase fondasi diperkaya dengan kolom OBE 2026: `curricula`, `courses`, `course_prerequisites`, `specializations`, `graduate_profiles`, dan `rps_documents`. Tabel `curriculum_imports` menyimpan riwayat inspect/dry-run/commit beserta checksum dan ringkasan validasi.

## Phase 04 Profile Tables

Tabel profil memperluas `pages`, `lecturers`, `management_positions`, `staff`, dan `partners`, serta menambah `expertise_areas`, `expertise_area_lecturer`, `lecturer_educations`, dan `accreditations`.

Database target adalah PostgreSQL. Website memakai database dan role tersendiri; data akademik otoritatif dibaca dari SINTESA melalui API terautentikasi, bukan melalui akses langsung ke database SINTESA.

Kolom detail tiap domain akan diperdalam sesuai fase fitur agar constraint dan relasi mengikuti kebutuhan aktual.
# Phase 05 Information Tables

Tahap 5 memperluas tabel `news_categories`, `news`, `announcements`, `events`, `albums`, `album_media`, `media`, `document_categories`, `documents`, dan `faqs`.

Prinsip penting:

- Tidak ada tabel duplikat untuk konten informasi.
- Konten publik disaring berdasarkan `status`, waktu publikasi/aktif, flag public, dan soft delete.
- Import legacy dicatat pada `content_migrations`; redirect lama pada `legacy_redirects`.

## Phase 06 Research Tables

Tahap 6 memperluas `research_projects`, `community_services`, `publications`, `digital_products`, `intellectual_properties`, `expertise_groups`, dan `journals`, serta menambah `research_partnerships`.

## Phase 07 Student Life and Admissions Tables

Tahap 7 memperluas `achievements`, `scholarships`, `competitions`, `alumni_stories`, `facilities`, `contact_messages`, dan `pages`, serta menambah `admission_paths`.

Aturan penting:

- Data prestasi publik tidak mengirim NIM lengkap atau sertifikat privat.
- Alumni publik wajib `consent_verified=true`.
- PMB tidak memuat jalur, tanggal, biaya, kuota, atau syarat tanpa verifikasi.
- Export kontak tidak menyertakan IP address atau user-agent secara default.

## Phase 08 Admin Operations Tables

Tahap 8 memperluas tabel `media`, `seo_metadata`, `legacy_redirects`, `admin_activity_logs`, dan `users`.

Tabel baru:

- `media_usages`
- `media_versions`
- `seo_settings`
- `navigation_items`
- `backup_records`

Aturan penting:

- Media yang masih memiliki usage tidak boleh dihapus permanen.
- Redirect lama menjaga kolom kompatibilitas `source_path`/`target_path` dan kolom operasional `old_path`/`new_path`.
- Backup record menyimpan checksum untuk verifikasi.
- Audit log tidak boleh menyimpan secret mentah.
