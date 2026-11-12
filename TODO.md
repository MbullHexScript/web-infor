# TODO Website Program Studi Informatika

## Fase 0 - Audit dan Setup

- [x] Audit workspace awal dan aset tersedia.
- [x] Tentukan versi stabil kompatibel dengan PHP lokal.
- [x] Scaffold Laravel, Inertia.js, React, TypeScript, Tailwind CSS.
- [x] Catat keterbatasan Git lokal.
- [x] Buat dokumentasi arsitektur awal.

## Fase 1 - Fondasi

- [x] Konfigurasi formatter, linter, type checking, dan test.
- [x] Buat design tokens CSS dan dark mode tanpa flicker.
- [x] Buat route bahasa ID/EN awal.
- [x] Buat public layout dengan navbar dua logo placeholder dan footer institusi.
- [x] Buat admin layout.
- [x] Buat autentikasi admin tunggal tanpa registrasi publik.
- [x] Tambahkan 2FA berbasis TOTP.
- [x] Buat migration inti awal.
- [x] Buat seeder site settings, quick links, statistik placeholder, dan tiga jurnal.
- [x] Tambahkan security headers dasar.
- [x] Ganti placeholder logo dengan aset resmi setelah aset tersedia.

## Fase 2 - Homepage

- [x] Lengkapi semua section homepage sesuai narasi prompt.
- [x] Tambahkan animasi ringan: hero reveal, network, lighthouse beam, sticky navbar.
- [x] Sambungkan data homepage ke database dan admin.
- [x] Uji responsive 360 px sampai 1920 px.
- [x] Tambahkan screenshot QA di `docs/qa/homepage/`.

## Fase 3 - Akademik

- [x] Importer Excel Kurikulum OBE 2026 idempotent dengan dry-run dari `storage/app/imports/curriculum/Draf_Kurikulum_OBE_2026_S1_Informatika_UIN_SMH_Banten Rev.1.xlsx`.
- [x] Halaman kurikulum, semester, mata kuliah, peminatan, dan profil lulusan.
- [x] Repositori RPS publik, preview fallback, dan download PDF.
- [ ] Lengkapi nama Inggris mata kuliah.
- [ ] Unggah PDF RPS resmi per mata kuliah.
- [ ] Tambahkan dokumen kalender akademik, jadwal kuliah, dan dokumen akademik publik ketika tersedia resmi.

## Fase 4-10

- [x] Fase 4 profil publik dan admin dasar.
- [x] Migrasi dosen legacy dengan dry-run dan import idempotent.
- [x] Halaman akreditasi aman tanpa data fiktif.
- [ ] Verifikasi data dosen legacy dan ubah status menjadi verified setelah diperiksa.
- [ ] Lengkapi tautan akademik, pendidikan, publikasi, dan bidang keahlian dosen.
- [ ] Verifikasi data akreditasi resmi.
- [ ] Verifikasi mitra dan dokumen kerja sama.
- [x] Fase 5 modul informasi publik: berita, pengumuman, agenda, galeri, unduhan, FAQ, dan search.
- [x] Command migrasi konten lama dengan inspect dan dry-run.
- [ ] Perluas dashboard informasi menjadi CRUD editor penuh untuk berita, pengumuman, agenda, galeri, dokumen, dan FAQ.
- [ ] Review editorial konten legacy sebelum publikasi.
- [x] Fase 6 riset dan inovasi publik: penelitian, pengabdian, publikasi, produk, HKI, kelompok keahlian, rumah jurnal, kerja sama riset.
- [x] Seed NEXUS, DIGIMAS, ABDIMATEK dengan status preparing tanpa data fiktif.
- [x] Command migrasi riset legacy dengan inspect dan dry-run.
- [ ] Perluas dashboard riset menjadi CRUD editor penuh.
- [ ] Verifikasi data riset, publikasi, produk, HKI, kelompok keahlian, dan kerja sama.
- [x] Fase 7 kemahasiswaan, prestasi, beasiswa, kompetisi, MBKM/magang, alumni, HMPS, fasilitas, PMB, dan kontak publik.
- [x] Form kontak aman dengan honeypot, rate limit, dan admin inbox.
- [x] Command migrasi kemahasiswaan legacy dengan inspect dan dry-run.
- [ ] Perluas dashboard kemahasiswaan/fasilitas/PMB menjadi CRUD editor penuh.
- [ ] Isi data resmi prestasi, beasiswa, kompetisi, alumni consent, fasilitas, jalur PMB, dan kontak terverifikasi.
- [x] Fase 8 dashboard operasional admin, media library, SEO manager, redirect manager, settings, audit log, backup, health check, maintenance mode, dan security hardening.
- [ ] Sambungkan backup terenkripsi off-server di production.
- [ ] Jalankan `mail:test` setelah SMTP production siap.
- [ ] Review CSP setelah CDN, analytics, font, iframe, dan integrasi eksternal final.
- [x] Fase 9 quality dashboard, audit commands, master content inventory, UAT checklist, dan draft release candidate notes.
- [ ] Unggah dan verifikasi seluruh RPS resmi.
- [ ] Lengkapi terjemahan English mata kuliah dari sumber resmi.
- [ ] Verifikasi final dosen, struktur organisasi, akreditasi, mitra, fasilitas, PMB, prestasi, dan kontak.
- [ ] Jalankan Lighthouse, browser compatibility, responsive screenshots, dan accessibility manual pass pada staging.
- [x] Fase 10 deployment runbook, env templates, Nginx template, scripts deploy/rollback/smoke, UAT issue tracking, handover, maintenance plan, and go-live docs.
- [ ] Provision staging server and run `scripts/deploy-staging.sh`.
- [ ] Complete UAT and resolve Critical/High issues.
- [ ] Provision production server, DNS, SSL, Cloudflare, queue, scheduler, monitoring, and backup.
- [ ] Resolve or formally accept Vite/esbuild dependency audit advisory.
- [ ] Run restore test in non-production.
- [ ] Execute production smoke test after go-live.

- [ ] Profil dan dosen.
- [x] Informasi: berita, pengumuman, agenda, galeri, dokumen, FAQ, search.
- [x] Riset dan rumah jurnal.
- [x] Mahasiswa, alumni, HMPS.
- [x] Media library, SEO manager, redirect manager, settings, audit log, dan dashboard operasional admin.
- [ ] Admin CRUD lengkap untuk semua modul konten.
- [ ] Migrasi konten website lama.
- [ ] QA, staging, dan release.
