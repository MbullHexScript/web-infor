# SEO

## Academic Pages

Halaman akademik mengirim title dan description spesifik untuk kurikulum, struktur mata kuliah, dan repositori RPS. Detail mata kuliah disiapkan untuk metadata lebih lanjut pada fase berikutnya.

## Profile Pages

Halaman profil mengirim title dan description dasar untuk modul profil. Detail dosen disiapkan untuk metadata Person ketika tautan akademik dan data verified sudah lengkap.

Fase 2 menambahkan SEO homepage:

- Meta title dan description ID/EN.
- Canonical.
- Hreflang ID/EN.
- Open Graph image.
- Twitter card.
- JSON-LD `EducationalOrganization`.

Sitemap, robots lanjutan, dan metadata dinamis per halaman detail tetap masuk fase SEO/konten berikutnya.

## Phase 07

Halaman kemahasiswaan, prestasi, beasiswa, kompetisi, alumni, fasilitas, PMB, dan kontak mengirim meta title/description dasar melalui Inertia `Head`.

Structured data dibuat konservatif pada tahap ini. Jangan memasukkan NIM, kontak alumni, sertifikat sensitif, atau data PMB belum terverifikasi ke metadata.

Pekerjaan lanjutan:

- Breadcrumb JSON-LD.
- FAQPage untuk PMB.
- ContactPoint setelah kontak resmi lengkap.
- Event schema kompetisi hanya jika tanggal/lokasi lengkap.

## Phase 08

SEO manager tersedia di `/admin/seo`.

Tambahan:

- Global SEO settings.
- Sitemap generation dan validation.
- Robots.txt dengan sitemap resmi.
- Admin noindex melalui header.
- `seo:audit` untuk cakupan metadata.

Periksa ulang canonical, hreflang, dan schema detail setelah semua konten resmi selesai diverifikasi.

## Phase 09

SEO final report tersedia di `docs/qa/seo-final-report.md`. `quality:audit` ikut merangkum missing title, missing description, duplicate title, media alt text, sitemap, dan manual structured data checks.
