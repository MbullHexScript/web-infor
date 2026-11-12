# Content Model

## Academic Content

Kurikulum OBE 2026 bersumber dari workbook resmi. Data publik memakai status `published`, `verified`, dan `is_active=true`. RPS publik memakai `is_public=true`, `is_current=true`, dan file PDF di disk `public`.

## Profile Content

Konten profil memakai halaman `pages` dengan key `profile.*`. Data legacy dosen dan mitra disimpan sebagai `needs_review`; production hanya menampilkan data verified.

Konten publik menggunakan status publikasi, status verifikasi, metadata JSON, dan field bilingual ID/EN. Data yang belum diverifikasi tidak boleh ditampilkan sebagai klaim resmi.

## Research Content

Konten riset menggunakan filter `is_public=true`, `verification_status=verified`, dan publikasi yang sudah layak tampil. Rumah jurnal NEXUS, DIGIMAS, dan ABDIMATEK berada pada status awal `preparing` tanpa ISSN, editor, OJS, akreditasi, atau indexing fiktif.

## Student Life and Admissions Content

Konten kemahasiswaan memakai prinsip privacy-first:

- Prestasi hanya tampil jika public, verified, dan published.
- Sertifikat hanya tampil jika ditandai aman untuk publik.
- Alumni hanya tampil jika consent terverifikasi.
- Beasiswa, kompetisi, fasilitas, dan jalur PMB tidak boleh memuat data resmi fiktif.
- Pesan kontak tidak tampil publik dan dikelola melalui inbox admin.
