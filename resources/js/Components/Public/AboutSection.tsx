import { ResponsiveImage } from '@/Components/Public/ResponsiveImage';
import { Locale } from '@/types/homepage';
import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';

export function AboutSection({ locale }: { locale: Locale }) {
    const isEnglish = locale === 'en';

    return (
        <div className="bg-white/72 grid items-center gap-10 rounded-[1.35rem] border border-white/80 p-5 shadow-[0_24px_80px_rgba(57,42,82,.10)] backdrop-blur-xl dark:border-white/10 dark:bg-[#1c2128] lg:grid-cols-[.9fr_1.1fr] lg:p-7">
            <div className="relative overflow-hidden rounded-[var(--radius-lg)] border border-white/80 bg-white p-4 shadow-[var(--shadow-md)] dark:border-slate-700 dark:bg-[#1c2128]">
                <div className="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(251,195,31,.28),transparent_32%),radial-gradient(circle_at_80%_70%,rgba(143,114,183,.34),transparent_35%),linear-gradient(135deg,rgba(7,9,24,.06),transparent_45%)]" />
                <ResponsiveImage
                    src="/assets/brand/logo-informatika-512.webp"
                    alt="Logo Program Studi Informatika"
                    width={512}
                    height={512}
                    className="relative mx-auto aspect-square max-h-80 w-full object-contain"
                />
            </div>
            <div className="lg:pr-4">
                <p className="text-xs font-bold uppercase tracking-[0.18em] text-brand-deep dark:text-brand-lavender">
                    {isEnglish ? 'About Informatics' : 'Tentang Informatika'}
                </p>
                <h2 className="mt-3 font-heading text-3xl font-bold tracking-normal text-brand-charcoal dark:text-white md:text-4xl">
                    {isEnglish
                        ? 'Building Trusted Digital Innovation for Society'
                        : 'Membangun Amanah Digital untuk Kemaslahatan'}
                </h2>
                <p className="mt-5 text-base leading-8 text-brand-graphite dark:text-slate-300">
                    {isEnglish
                        ? 'The Informatics Study Program at the Faculty of Science and Technology, Sultan Maulana Hasanuddin State Islamic University Banten, advances education, research, and digital innovation by integrating professional competence, ethical responsibility, and Islamic values.'
                        : 'Program Studi Informatika Fakultas Sains dan Teknologi UIN Sultan Maulana Hasanuddin Banten mengembangkan pendidikan, penelitian, dan inovasi di bidang teknologi digital dengan mengintegrasikan kompetensi profesional, tanggung jawab etis, dan nilai-nilai keislaman.'}
                </p>
                <div className="bg-brand-lavender/20 mt-6 rounded-[var(--radius-md)] border-l-4 border-brand-red p-4 text-sm font-bold uppercase leading-6 tracking-wide text-brand-deep dark:text-brand-lavender">
                    {isEnglish
                        ? 'DIGITAL TRUST FOR PUBLIC BENEFIT AND CYBER RESILIENCE IN BANTEN'
                        : 'AMANAH DIGITAL UNTUK KEMASLAHATAN DAN RESILIENSI SIBER BANTEN'}
                </div>
                <Link
                    href={isEnglish ? '/en/profile/about' : '/id/profil/tentang'}
                    className="mt-7 inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-sm)] bg-brand-deep px-5 py-3 text-sm font-bold text-white"
                >
                    {isEnglish ? 'Read More' : 'Baca Selengkapnya'}
                    <ArrowRight size={18} />
                </Link>
            </div>
        </div>
    );
}
