# Changelog

## 2026-07-12 - Fase 10 Staging, Deployment Prep, dan Go-Live Runbook

- Menambahkan `.env.staging.example` dan `.env.production.example` tanpa secret asli.
- Menambahkan template Nginx production, Cloudflare real-IP, systemd queue worker, scheduler cron, logrotate, dan robots staging.
- Menambahkan script `deploy-staging.sh`, `deploy-production.sh`, `rollback-production.sh`, dan `smoke-test-production.sh`.
- Menambahkan dokumen deployment: staging, DNS, Cloudflare, hardening server, queue/scheduler, backup automation, monitoring, rollback, legacy site retirement, go-live checklist, approval, deployment record, incident response, email, Search Console/analytics, final deployment test report.
- Menambahkan handover admin/teknis, maintenance plan, update strategy, UAT issue tracking, dan release notes v1.0.0 template.
- Mengubah HSTS preload menjadi opt-in via `HSTS_PRELOAD=false` dan memberi `X-Robots-Tag` noindex untuk staging.
- Status: production belum dideploy; UAT, server, DNS, SSL, Cloudflare, restore test, email, dan approval masih manual/pending.

## 2026-07-12 - Fase 9 Quality, UAT, dan Release Candidate Prep

- Menambahkan `translations:audit`, `db:audit-performance`, dan `quality:audit`.
- Menambahkan dashboard `/admin/quality`, content verification, dan RPS coverage.
- Membuat master content inventory CSV/Markdown dan laporan baseline Phase 9.
- Menambahkan `UatSeeder` dengan guard agar tidak berjalan di production.
- Menambahkan dokumentasi final QA, SEO, redirect, broken links, accessibility, performance, database performance, security, browser compatibility, UAT checklist, dan draft release notes.
- Menandai blocker UAT: RPS resmi belum diunggah, data dosen masih perlu verifikasi, dan terjemahan mata kuliah English masih incomplete.

## 2026-07-12 - Fase 8 Operasional Admin dan Security

- Menambahkan dashboard admin operasional dengan ringkasan konten, media, SEO, redirect, backup, security, dan item perlu verifikasi.
- Menambahkan media library dengan upload hardening, metadata, replacement versioning, usage guard, audit, dan cleanup command.
- Menambahkan SEO manager, sitemap generator/validator, redirect manager, site settings, navigation settings, audit log, account security, health check, backup status, dan maintenance toggle.
- Memperkuat security headers dengan CSP, COOP, CORP, admin noindex, validasi upload, redirect safety, dan redaksi data sensitif di audit log.
- Menambahkan migration operasional untuk media usage/version, SEO settings, navigation items, backup records, dan ekstensi tabel terkait.
- Menambahkan test Phase 8 untuk proteksi admin, upload hardening, redirect safety, security headers, health endpoint, sitemap, backup, cleanup, audit command, 2FA, dan recovery codes.

## 2026-07-12 - Fase 7 Kemahasiswaan, PMB, Fasilitas, Kontak

- Menambahkan modul publik bilingual untuk kemahasiswaan, prestasi, beasiswa, kompetisi, MBKM/magang, alumni, HMPS, fasilitas, PMB, dan kontak.
- Memperluas tabel `achievements`, `scholarships`, `competitions`, `alumni_stories`, `facilities`, `contact_messages`, dan `pages`, serta menambah `admission_paths`.
- Menambahkan form kontak dengan validasi, consent, honeypot, rate limit, penyimpanan database, dan toleransi kegagalan email.
- Menambahkan admin dashboard kemahasiswaan, fasilitas, PMB, dan inbox kontak dengan status read/replied/archived/spam.
- Menambahkan command `student-life:inspect-legacy` dan `student-life:import-legacy` dengan dry-run, idempotency, laporan, dan pending verification.
- Menambahkan test Phase 7 untuk route, privacy guard, filter, consent alumni, kontak, admin inbox, dan migrasi.

## 2026-07-12 - Fase 6 Riset dan Inovasi

- Menambahkan modul publik Riset dan Inovasi: penelitian, pengabdian, publikasi, produk digital, HKI, kelompok keahlian, rumah jurnal, dan kerja sama riset.
- Memperluas tabel riset yang sudah ada tanpa membuat duplikat.
- Menambahkan seed final NEXUS, DIGIMAS, dan ABDIMATEK dengan status `preparing` tanpa ISSN/editor/OJS fiktif.
- Menambahkan dashboard admin riset dan command `research:inspect-legacy` serta `research:import-legacy`.
- Menambahkan test backend Phase 6 untuk route, visibility, filter, detail, citation export, jurnal preparing, produk/HKI/pengabdian, dan dry-run legacy.

## 2026-07-12 - Fase 5 Modul Informasi

- Menambahkan modul informasi publik: berita, pengumuman, agenda, galeri, pusat unduhan, FAQ, dan pencarian global.
- Memperluas tabel konten yang sudah ada tanpa membuat duplikat untuk `news`, `announcements`, `events`, `albums`, `documents`, dan `faqs`.
- Menambahkan dashboard admin informasi dan route admin untuk news, announcements, events, gallery, documents, dan FAQs.
- Menambahkan command `content:inspect-legacy` dan `content:import-legacy` dengan dry-run, pending verification, dan redirect legacy.
- Menambahkan test Phase 5 untuk visibilitas berita, pengumuman expired, agenda/ICS, unduhan, FAQ, search, dan dry-run legacy.

## 2026-07-12 - Fase 4 Profil

- Menambahkan modul profil publik dan admin: tentang, sejarah, visi/misi, sambutan, struktur pengelola, dosen, staff, profil lulusan, akreditasi, dan kerja sama.
- Menambahkan migrasi legacy dosen dengan inspect, dry-run, commit idempotent, download foto lokal, dan laporan import.
- Memigrasikan 8 dosen dan 6 mitra awal dari website lama sebagai data `needs_review`.
- Menahan publikasi akreditasi sampai nomor keputusan dan masa berlaku terverifikasi.

## 2026-07-12 - Fase 3 Akademik

- Menambahkan importer Kurikulum OBE 2026 dengan inspect, dry-run, commit, validasi, dan laporan import.
- Menambahkan migrasi akademik, riwayat import, halaman publik akademik, dashboard/import admin, dan upload RPS PDF dasar.
- Mengimpor 65 mata kuliah, 144 SKS paket mahasiswa, 3 peminatan, dan 4 profil lulusan dari workbook resmi.

## 2026-07-12 - Fase 0-1

- Scaffold Laravel 12.63.0 karena template Laravel terbaru membutuhkan PHP 8.3, sedangkan lingkungan lokal memakai PHP 8.2.12.
- Menambahkan Inertia.js, React 18, TypeScript, Tailwind CSS, Breeze auth, Google2FA, Lucide icons, ESLint, dan Prettier.
- Menambahkan route publik `/id` dan `/en`, route admin `/admin`, dan login `/admin/login`.
- Menonaktifkan registrasi publik dari route aplikasi.
- Menambahkan kolom dan flow 2FA TOTP untuk admin.
- Menambahkan design tokens, light/dark mode tanpa flicker, public layout, admin layout, navbar dua logo placeholder, dan footer identitas lengkap.
- Menambahkan migration kerangka tabel inti, seeder site settings, quick links, statistik placeholder, dan jurnal NEXUS, DIGIMAS, ABDIMATEK.
- Menambahkan security headers dasar.
- Catatan: Git tidak tersedia di PATH pada lingkungan ini, sehingga inisialisasi Git belum dapat dijalankan.
- Catatan: `npm audit` masih melaporkan 2 vulnerability dari dependency frontend transitive; belum dipaksa fix karena rekomendasi npm membutuhkan perubahan breaking.

## 2026-07-12 - Aset Resmi

- Menambahkan logo UIN SMH Banten dan logo Program Studi Informatika ke `public/assets/brand`.
- Membuat varian WebP 512px untuk pemakaian navbar/footer.
- Mengganti placeholder logo dengan gambar resmi.
- Memperbarui token `--color-accent-red` menjadi `#bb2e1c` dan `--color-accent-yellow` menjadi `#fbc31f` berdasarkan logo Informatika.
- Menyimpan dokumen Kurikulum OBE 2026 ke `storage/app/imports/curriculum/` sebagai sumber importer Fase 3.
- Menginisialisasi repository Git lokal dan memakai branch awal `main`.

## 2026-07-12 - Fase 2 Homepage Publik

- Membangun homepage publik modular untuk `/id` dan `/en`.
- Menambahkan top bar, sticky navbar glass, dropdown desktop, mobile drawer dengan Escape dan body scroll lock, language switcher, search button, dan footer empat kolom.
- Menambahkan hero premium dengan animated network, lighthouse beam satu kali per sesi, CTA, SEO metadata, hreflang, Open Graph, dan JSON-LD EducationalOrganization.
- Menambahkan section: pengumuman penting, akses cepat, tentang, statistik terverifikasi, bidang keilmuan, Kurikulum OBE 2026, sambutan Kaprodi, dosen, riset, rumah jurnal, berita/pengumuman/agenda, prestasi, fasilitas, HMPS, dan PMB CTA.
- Menambahkan admin `/admin/homepage` untuk mengatur homepage sections, quick links, dan statistics.
- Menambahkan cache homepage per locale dan invalidasi cache setelah update admin.
- Menambahkan migration ekstensi homepage dan seed awal yang aman tanpa statistik/ISSN/akreditasi fiktif.
- Menambahkan test homepage: locale, active section, expired announcement, inactive statistics, verified lecturers, active journals, cache, cache invalidation, dan external quick link state.
- Menyimpan visual QA screenshots di `docs/qa/homepage/`.
