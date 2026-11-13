import { Locale } from '@/types/homepage';
import { Link } from '@inertiajs/react';
import { ChevronDown } from 'lucide-react';

const items = [
    {
        label_id: 'Profil',
        label_en: 'Profile',
        href_id: '/id/profil',
        href_en: '/en/profile',
        children: [
            ['/id/profil/tentang', '/en/profile/about', 'Tentang Informatika', 'About Informatics'],
            ['/id/profil/dosen', '/en/profile/lecturers', 'Dosen', 'Lecturers'],
            ['/id/profil/sambutan', '/en/profile/head-message', 'Sambutan Kaprodi', 'Head Message'],
        ],
    },
    {
        label_id: 'Akademik',
        label_en: 'Academics',
        href_id: '/id/akademik/kurikulum',
        href_en: '/en/academics/curriculum',
        children: [
            [
                '/id/akademik/kurikulum',
                '/en/academics/curriculum',
                'Kurikulum OBE 2026',
                'OBE 2026 Curriculum',
            ],
            [
                '/id/akademik/mata-kuliah',
                '/en/academics/courses',
                'Struktur Mata Kuliah',
                'Course Structure',
            ],
            ['/id/akademik/rps', '/en/academics/course-plans', 'Repositori RPS', 'Course Plans'],
        ],
    },
    {
        label_id: 'Riset & Inovasi',
        label_en: 'Research',
        href_id: '/id/riset',
        href_en: '/en/research',
        children: [
            ['/id/riset', '/en/research', 'Riset dan Inovasi', 'Research and Innovation'],
            [
                '/id/riset/penelitian',
                '/en/research/projects',
                'Penelitian Dosen',
                'Research Projects',
            ],
            [
                '/id/riset/pengabdian',
                '/en/research/community-service',
                'Pengabdian',
                'Community Service',
            ],
            ['/id/riset/publikasi', '/en/research/publications', 'Publikasi', 'Publications'],
            ['/id/riset/produk', '/en/research/products', 'Produk dan Aplikasi', 'Products'],
            ['/id/riset/hki', '/en/research/intellectual-property', 'HKI', 'Intellectual Property'],
            [
                '/id/riset/kelompok-keahlian',
                '/en/research/expertise-groups',
                'Kelompok Keahlian',
                'Expertise Groups',
            ],
            ['/id/riset/rumah-jurnal', '/en/research/journals', 'Rumah Jurnal', 'Journal Hub'],
            [
                '/id/riset/kerja-sama',
                '/en/research/partnerships',
                'Kerja Sama Riset',
                'Partnerships',
            ],
        ],
    },
    {
        label_id: 'Kemahasiswaan',
        label_en: 'Student Life',
        href_id: '/id/kemahasiswaan',
        href_en: '/en/student-life',
        children: [
            ['/id/kemahasiswaan', '/en/student-life', 'Kemahasiswaan', 'Student Life'],
            [
                '/id/kemahasiswaan/kehidupan-mahasiswa',
                '/en/student-life/campus-life',
                'Kehidupan Mahasiswa',
                'Campus Life',
            ],
            [
                '/id/kemahasiswaan/prestasi',
                '/en/student-life/achievements',
                'Prestasi',
                'Achievements',
            ],
            [
                '/id/kemahasiswaan/beasiswa',
                '/en/student-life/scholarships',
                'Beasiswa',
                'Scholarships',
            ],
            [
                '/id/kemahasiswaan/kompetisi',
                '/en/student-life/competitions',
                'Kompetisi',
                'Competitions',
            ],
            [
                '/id/kemahasiswaan/mbkm-magang',
                '/en/student-life/mbkm-internships',
                'MBKM dan Magang',
                'MBKM and Internships',
            ],
            ['/id/kemahasiswaan/alumni', '/en/student-life/alumni', 'Alumni', 'Alumni'],
            [
                '/id/kemahasiswaan/hmps',
                '/en/student-life/student-association',
                'HMPS Informatika',
                'HMPS Informatics',
            ],
        ],
    },
    {
        label_id: 'Informasi',
        label_en: 'Information',
        href_id: '/id/informasi',
        href_en: '/en/information',
        children: [
            ['/id/informasi', '/en/information', 'Informasi', 'Information'],
            ['/id/informasi/berita', '/en/information/news', 'Berita', 'News'],
            [
                '/id/informasi/pengumuman',
                '/en/information/announcements',
                'Pengumuman',
                'Announcements',
            ],
            ['/id/informasi/agenda', '/en/information/events', 'Agenda', 'Events'],
            ['/id/informasi/galeri', '/en/information/gallery', 'Galeri', 'Gallery'],
            ['/id/informasi/unduhan', '/en/information/downloads', 'Pusat Unduhan', 'Downloads'],
            ['/id/informasi/faq', '/en/information/faq', 'FAQ', 'FAQ'],
        ],
    },
    {
        label_id: 'PMB',
        label_en: 'Admissions',
        href_id: 'https://pmb.uinbanten.ac.id/',
        href_en: 'https://pmb.uinbanten.ac.id/',
        children: [
            [
                'https://pmb.uinbanten.ac.id/',
                'https://pmb.uinbanten.ac.id/',
                'Portal PMB Resmi',
                'Official Admissions Portal',
            ],
            ['/id/akademik/kurikulum', '/en/academics/curriculum', 'Kurikulum', 'Curriculum'],
            ['/id/fasilitas', '/en/facilities', 'Fasilitas', 'Facilities'],
            ['/id/kontak', '/en/contact', 'Kontak', 'Contact'],
        ],
    },
] as const;

export function DesktopNavigation({ locale }: { locale: Locale }) {
    return (
        <nav
            className="public-navigation hidden items-center gap-1 lg:flex"
            aria-label="Navigasi utama"
        >
            <Link
                href={`/${locale}`}
                className="bg-brand-lavender/20 ring-brand-lavender/30 hover:bg-brand-lavender/30 rounded-full px-3 py-2 text-sm font-black text-brand-deep ring-1 transition dark:text-brand-lavender"
            >
                {locale === 'id' ? 'Beranda' : 'Home'}
            </Link>
            {items.map((item) => (
                <div key={item.label_id} className="group relative">
                    <a
                        href={locale === 'id' ? item.href_id : item.href_en}
                        target={
                            (locale === 'id' ? item.href_id : item.href_en).startsWith('http')
                                ? '_blank'
                                : undefined
                        }
                        rel={
                            (locale === 'id' ? item.href_id : item.href_en).startsWith('http')
                                ? 'noopener noreferrer'
                                : undefined
                        }
                        className="inline-flex items-center gap-1 rounded-full px-3 py-2 text-sm font-bold text-brand-graphite transition hover:bg-white/60 hover:text-brand-deep focus:bg-white/60 dark:text-slate-200 dark:hover:bg-white/10"
                    >
                        <span className="whitespace-nowrap">
                            {locale === 'id' ? item.label_id : item.label_en}
                        </span>
                        <ChevronDown size={14} />
                    </a>
                    <div className="invisible absolute left-0 top-full z-50 w-72 translate-y-1 pt-2 opacity-0 transition group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                        <div className="public-dropdown rounded-2xl border border-white/70 bg-white/95 p-2 shadow-[0_24px_70px_rgba(23,27,33,.18)] backdrop-blur-2xl dark:border-white/10 dark:bg-[#1c2128]/95">
                            {item.children.map(([idHref, enHref, idLabel, enLabel]) => {
                                const href = locale === 'id' ? idHref : enHref;
                                const external = href.startsWith('http');
                                return (
                                    <a
                                        key={idHref}
                                        href={href}
                                        target={external ? '_blank' : undefined}
                                        rel={external ? 'noopener noreferrer' : undefined}
                                        className="hover:bg-brand-lavender/20 block rounded-xl px-3 py-2.5 text-sm font-semibold text-brand-graphite transition hover:text-brand-deep dark:text-slate-200"
                                    >
                                        {locale === 'id' ? idLabel : enLabel}
                                    </a>
                                );
                            })}
                        </div>
                    </div>
                </div>
            ))}
        </nav>
    );
}
