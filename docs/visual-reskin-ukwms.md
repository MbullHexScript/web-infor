# Web Infor — laporan visual reskin

Tanggal: 13 November 2026. Branch: main. Implementasi setelah audit dan persetujuan pengguna.

## Arah visual

Referensi: https://informatics.ukwms.ac.id/id. Homepage serta halaman kurikulum dan dosen ditinjau secara visual: sans-serif besar, whitespace lapang, surface netral, kartu rounded, border tipis, tombol pill dan aksen terbatas. Desktop, mobile dan tema gelap ditinjau. Halaman berita referensi tidak berhasil ditinjau. Identitas UKWMS tidak disalin; logo, warna ungu dan seluruh konten Web Infor dipertahankan.

Inventaris audit: 67 halaman publik, 31 komponen publik, 4 komponen Layout, dan lima kelompok komponen domain. HomePage adalah homepage aktif. Komponen lama/tidak terpasang tidak ditambahkan ke halaman.

## Token publik

| Token | Light | Dark |
| --- | --- | --- |
| Surface | #ffffff | #1c2128 |
| Teks sekunder | #666b73 | #b8bec8 |
| Tint | #f1edf7 | #292331 |
| Aksi | #5a4477 | #c9b8e6 |
| Teks pada aksi | #ffffff | #171b21 |
| Border kontrol | #85808d | #8b8497 |

Heading memakai font stack existing, bobot 600, ukuran H1 36–64px; section heading 30–48px. Spacing section 56px mobile/80px desktop. Radius 12/16/24px dan pill. Tidak menambah font, dependency, atau mengubah konfigurasi Tailwind. Token dibatasi .public-site agar admin/auth mempertahankan desain existing.

## Preservasi

- Perbandingan AST pada 65 file TSX lulus: tidak ada perubahan selain className, whitespace JSX dan tanda kurung format. Props, state, handler, conditional rendering, teks, URL, import dan struktur komponen tetap.
- Inventaris DOM homepage sebelum/sesudah sama: 74 tautan, 10 gambar, 4 kontrol; teks main sama.
- Urutan homepage tetap: hero/overview, pengumuman opsional, akses cepat, sambutan, tentang, berita/informasi, PMB, footer.
- Tidak ada perubahan controller, model, migration, API, auth, middleware, route, aset, environment, kredensial atau dependency. Tidak ada commit/push/PR atau branch baru.

## Validasi

- npm run typecheck: lulus.
- npm run lint: lulus.
- npm run format:check: lulus.
- npm run build: lulus (Vite produksi).
- git diff --check: lulus.
- Responsif 320, 390, 768, 1024, 1440px: tidak ditemukan overflow horizontal halaman atau kontrol header terpotong pada pemeriksaan DOM.
- Homepage desktop/mobile light/dark ditinjau; profil, kurikulum/mata kuliah, riset, kemahasiswaan dan informasi ditinjau. ID/EN tetap dapat dinavigasi.
- Drawer membuka/menutup, Escape, scroll lock dan focus wrap diuji; pause/play pengumuman, theme toggle, search autofocus/Escape diuji. Search overlay akhir top=0 dan tinggi=900 pada viewport 900px. Semester 2 mengubah URL ke ?semester=2 dan selected tab sesuai.
- Screenshot sebelum/sesudah dibandingkan. Konten lokal akademik sebagian kosong; tidak menambahkan seed/data. Form pengiriman, seluruh detail konten, PDF/lightbox dan seluruh kombinasi browser tidak diuji end-to-end. Ini bukan sertifikasi WCAG atau jaminan semua state dinamis sudah diuji.

## Bukti lokal

Folder artefak: C:/Users/Pongo/.codex/visualizations/2026/11/13/01a1d38c-3800-7c30-929c-a6a0a4248d80/reskin/

- before-desktop.png / after-desktop.png
- before-mobile.png / after-mobile.png
- after-desktop-hero.png / after-mobile-hero.png
- after-desktop-dark.png / after-mobile-dark.png
- after-profile-dark.png / after-curriculum-dark.png
- before-home-inventory.json / after-home-inventory.json
- preservation-check.json / viewport-checks.json

Artefak QA berada di mesin lokal, tidak masuk aset produksi.

## Perubahan per file

| File | Perubahan visual |
| --- | --- |
| `resources/css/app.css` | Token publik light/dark, tipografi editorial, whitespace, kartu, tombol, form, tabel, prose, navigasi, focus dan breakpoint; seluruh tambahan dibatasi .public-site. |
| `resources/js/Components/Academic/AcademicComponents.tsx` | Shell publik dan hero konsisten; surface kartu, tombol, form atau konten domain memakai hook visual publik. Ekspor yang dipakai admin tidak diubah secara fungsional. |
| `resources/js/Components/Information/InformationComponents.tsx` | Shell publik dan hero konsisten; surface kartu, tombol, form atau konten domain memakai hook visual publik. Ekspor yang dipakai admin tidak diubah secara fungsional. |
| `resources/js/Components/Layout/LogoMark.tsx` | Hook border dan shadow logo; aset asli tetap. |
| `resources/js/Components/Layout/NavbarBrand.tsx` | Hook brand, bobot dan spacing label. |
| `resources/js/Components/Layout/ThemeToggle.tsx` | Kontrol tema bundar. |
| `resources/js/Components/Profile/ProfileComponents.tsx` | Shell publik dan hero konsisten; surface kartu, tombol, form atau konten domain memakai hook visual publik. Ekspor yang dipakai admin tidak diubah secara fungsional. |
| `resources/js/Components/Public/AboutSection.tsx` | Band netral, layout existing lebih lapang, framing gambar lebih tenang. |
| `resources/js/Components/Public/AdmissionCtaSection.tsx` | CTA ungu gelap, headline besar, kontras tombol kuning. |
| `resources/js/Components/Public/AnnouncementStrip.tsx` | Lebar sejajar konten utama, shadow halus, badge prioritas kontras. |
| `resources/js/Components/Public/DesktopNavigation.tsx` | Bobot teks lebih ringan, surface dropdown dan pill navigasi. |
| `resources/js/Components/Public/EmptyState.tsx` | Surface empty state konsisten. |
| `resources/js/Components/Public/Footer.tsx` | Surface dan spacing footer konsisten. |
| `resources/js/Components/Public/GlobalSearchButton.tsx` | Kontrol bundar, panel pencarian berpadding lega, tombol pill. |
| `resources/js/Components/Public/HeadMessageSection.tsx` | Spacing dan skala kutipan editorial. |
| `resources/js/Components/Public/HomeHero.tsx` | Headline lebih besar, proporsi kolom seimbang, gradien ungu tenang, backing logo putih, tombol pill dan overview yang lebih ringan. |
| `resources/js/Components/Public/LanguageSwitcher.tsx` | Kontrol bahasa pill. |
| `resources/js/Components/Public/LoadingSkeleton.tsx` | Surface skeleton konsisten. |
| `resources/js/Components/Public/MobileDrawer.tsx` | Permukaan drawer, backdrop gelap dan tinggi viewport dinamis. |
| `resources/js/Components/Public/Navbar.tsx` | Navbar solid, border halus; hapus backdrop filter agar fixed search overlay memenuhi viewport. |
| `resources/js/Components/Public/NewsInformationSection.tsx` | Shell publik dan hero konsisten; surface kartu, tombol, form atau konten domain memakai hook visual publik. Ekspor yang dipakai admin tidak diubah secara fungsional. |
| `resources/js/Components/Public/QuickLinksGrid.tsx` | Kartu ringan, tile ikon ungu, label aksi selalu terlihat. |
| `resources/js/Components/Public/SectionHeader.tsx` | Hook skala judul dan deskripsi section. |
| `resources/js/Components/Public/TopBar.tsx` | Latar charcoal konsisten dalam kedua tema. |
| `resources/js/Components/Research/ResearchComponents.tsx` | Shell publik dan hero konsisten; surface kartu, tombol, form atau konten domain memakai hook visual publik. Ekspor yang dipakai admin tidak diubah secara fungsional. |
| `resources/js/Components/StudentLife/StudentLifeComponents.tsx` | Shell publik dan hero konsisten; surface kartu, tombol, form atau konten domain memakai hook visual publik. Ekspor yang dipakai admin tidak diubah secara fungsional. |
| `resources/js/Pages/Public/Academics/AcademicCalendar.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Academics/AcademicDocuments.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Academics/ClassSchedule.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Academics/CourseDetail.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Academics/Courses.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Academics/Curriculum.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Academics/RpsPreview.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Academics/RpsRepository.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Academics/SpecializationDetail.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Academics/Specializations.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/HomePage.tsx` | Scope public-site, spacing antarseksi, alignment dan latar dekoratif lebih tenang. |
| `resources/js/Pages/Public/Information/AnnouncementDetail.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Information/EventDetail.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Placeholder.tsx` | Scope styling publik. |
| `resources/js/Pages/Public/Profile/AboutPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Profile/AccreditationPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Profile/GraduateProfilesPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Profile/HeadMessagePage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Profile/HistoryPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Profile/LecturerDetail.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Profile/LecturersDirectory.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Profile/ManagementPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Profile/PartnershipsPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Profile/ProfileLandingPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Profile/StaffPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Profile/VisionMissionPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Research/CommunityServiceDetailPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Research/JournalDetailPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Research/ProductDetailPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Research/ResearchDetailPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/Research/ResearchPartnershipsPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/StudentLife/AchievementDetailPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/StudentLife/AchievementsPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/StudentLife/AdmissionLandingPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/StudentLife/AlumniStoryPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/StudentLife/CompetitionDetailPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/StudentLife/HmpsPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/StudentLife/MbkmPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/StudentLife/ScholarshipDetailPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `resources/js/Pages/Public/StudentLife/StudentLifeLandingPage.tsx` | Class visual pada section, kartu atau tombol existing diselaraskan dengan token publik; struktur dan konten tetap. |
| `docs/visual-reskin-ukwms.md` | Laporan token, validasi, preservasi, bukti dan daftar perubahan ini. |
