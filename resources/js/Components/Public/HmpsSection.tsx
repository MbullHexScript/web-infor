import { Locale } from '@/types/homepage';
import { ExternalLink } from 'lucide-react';

export function HmpsSection({ locale, url }: { locale: Locale; url: string }) {
    const isEnglish = locale === 'en';

    return (
        <div className="from-brand-lavender/28 to-brand-yellow/18 rounded-[var(--radius-lg)] border border-brand-lavender bg-gradient-to-br via-white p-6 shadow-[var(--shadow-sm)] dark:border-slate-700 dark:from-[#1c2128] dark:via-[#1c2128] dark:to-[#33291b] md:p-8">
            <div className="max-w-3xl">
                <p className="text-xs font-bold uppercase tracking-[0.18em] text-brand-deep dark:text-brand-lavender">
                    HMPS Informatika
                </p>
                <h2 className="mt-3 font-heading text-3xl font-bold text-brand-charcoal dark:text-white">
                    {isEnglish
                        ? 'Informatics Student Association'
                        : 'Himpunan Mahasiswa Program Studi Informatika'}
                </h2>
                <p className="mt-4 text-base leading-7 text-brand-graphite dark:text-slate-300">
                    {isEnglish
                        ? 'A platform for Informatics students to grow, create, lead, and collaborate.'
                        : 'Ruang bagi mahasiswa Informatika untuk bertumbuh, berkarya, berorganisasi, dan berkolaborasi.'}
                </p>
                <a
                    href={url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="mt-6 inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-sm)] bg-brand-deep px-5 py-3 text-sm font-bold text-white"
                >
                    {isEnglish ? 'Visit HMPS Website' : 'Kunjungi Website HMPS'}
                    <ExternalLink size={18} />
                </a>
            </div>
        </div>
    );
}
