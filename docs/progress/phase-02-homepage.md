# Phase 02 Homepage

## 1. Ringkasan Implementasi

Homepage publik `/id` dan `/en` telah dibangun sebagai halaman Inertia React modular dengan data dari database, cache per locale, admin settings awal, animasi ringan, SEO homepage, dan visual QA responsive.

## 2. Komponen Baru

- `TopBar`
- `Navbar`
- `DesktopNavigation`
- `MobileDrawer`
- `LanguageSwitcher`
- `GlobalSearchButton`
- `HomeHero`
- `AnimatedNetwork`
- `LighthouseBeam`
- `AnnouncementStrip`
- `QuickLinksGrid`
- `AboutSection`
- `StatisticsSection`
- `StudyFieldsSection`
- `CurriculumSection`
- `HeadMessageSection`
- `LecturersSection`
- `ResearchSection`
- `JournalsSection`
- `NewsInformationSection`
- `AchievementsSection`
- `FacilitiesSection`
- `HmpsSection`
- `AdmissionCtaSection`
- `Footer`
- `SectionHeader`
- `Reveal`
- `Counter`
- `ResponsiveImage`
- `EmptyState`
- `LoadingSkeleton`

## 3. Migration Baru

- `2026_07_12_000003_extend_homepage_foundation_tables.php`

## 4. Seeder Baru

Seeder utama `FoundationSeeder` diperluas untuk:

- Hero ID/EN.
- Homepage section order.
- Enam quick links.
- Enam bidang keilmuan.
- Ringkasan Kurikulum OBE 2026.
- Tiga jurnal: NEXUS, DIGIMAS, ABDIMATEK.
- Pengumuman penting awal dengan status verifikasi `pending`.

## 5. Route Baru

- `/id`
- `/en`
- Placeholder CTA: `/{locale}/{path}`
- `/admin/homepage`
- `/admin/homepage/sections`
- `/admin/homepage/quick-links`
- `/admin/homepage/statistics`

## 6. Admin Feature

Admin dapat mengatur section, quick links, dan statistics. Update admin menghapus cache homepage ID/EN.

## 7. Animasi

- Hero reveal.
- Animated network.
- Lighthouse beam satu kali per sesi.
- Sticky navbar shrink.
- Announcement pulse yang dapat dipause.

Reduced motion didukung.

## 8. Accessibility

- Skip link.
- Satu H1.
- Drawer Escape close dan focus trap.
- Body scroll lock saat drawer terbuka.
- Focus ring global.
- External link aman.
- Tidak ada horizontal overflow pada viewport QA.

## 9. Performance

- Data homepage dibatasi per section.
- Query homepage dicache per locale selama 15 menit.
- Gambar logo memakai WebP optimized.
- Tidak memakai WebGL atau particle system berat.

## 10. Test

- `php artisan migrate:fresh --seed`: passed.
- `vendor\bin\pint`: passed.
- `npm.cmd run format:check`: passed.
- `npm.cmd run lint`: passed.
- `npm.cmd run typecheck`: passed.
- `npm.cmd run build`: passed.
- `php artisan test`: 35 passed, 81 assertions.

## 11. Visual QA

Screenshot tersimpan di `docs/qa/homepage/`:

- `homepage-360x800.png`
- `homepage-390x844.png`
- `homepage-768x1024.png`
- `homepage-1024x768.png`
- `homepage-1366x768.png`
- `homepage-1440x900.png`
- `homepage-1920x1080.png`

Hasil otomatis: tidak ada horizontal overflow, satu H1, logo termuat, dan tidak ada console error.

## 12. Masalah Tersisa

- Statistik tetap nonaktif sampai diverifikasi admin.
- Data dosen, berita, riset, prestasi, dan fasilitas resmi belum tersedia.
- Admin detail hero/media/featured content masih baseline dan akan diperluas pada fase admin lengkap.
- URL SIAKAD, E-Learning, Perpustakaan, dan PMB masih placeholder `#` sampai URL resmi diisi.

## 13. Cara Review

- Homepage ID: `http://127.0.0.1:8000/id`
- Homepage EN: `http://127.0.0.1:8000/en`
- Admin homepage: `http://127.0.0.1:8000/admin/homepage`
