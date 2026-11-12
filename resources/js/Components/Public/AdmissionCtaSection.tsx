import { Locale } from '@/types/homepage';
import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';

export function AdmissionCtaSection({ locale, pmbUrl }: { locale: Locale; pmbUrl: string }) {
    const isEnglish = locale === 'en';
    const external = pmbUrl.startsWith('http');

    return (
        <div className="overflow-hidden rounded-[var(--radius-lg)] bg-brand-deep p-6 text-white shadow-[var(--shadow-lg)] md:p-10">
            <div className="max-w-3xl">
                <h2 className="font-heading text-3xl font-bold tracking-normal md:text-4xl">
                    {isEnglish
                        ? 'Ready to Join the Next Generation of Digital Innovators?'
                        : 'Siap Menjadi Bagian dari Generasi Digital Masa Depan?'}
                </h2>
                <p className="text-white/78 mt-4 text-base leading-7">
                    {isEnglish
                        ? 'Discover an Informatics learning experience that integrates technology, innovation, and Islamic values.'
                        : 'Temukan pengalaman belajar Informatika yang memadukan teknologi, inovasi, dan nilai-nilai keislaman.'}
                </p>
                <div className="mt-7 flex flex-wrap gap-3">
                    <a
                        href={pmbUrl}
                        target={external ? '_blank' : undefined}
                        rel={external ? 'noopener noreferrer' : undefined}
                        className="inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-sm)] bg-brand-yellow px-5 py-3 text-sm font-bold text-brand-charcoal"
                    >
                        {isEnglish ? 'Admissions Information' : 'Informasi PMB'}
                        <ArrowRight size={18} />
                    </a>
                    <Link
                        href={isEnglish ? '/en/academics/curriculum' : '/id/akademik/kurikulum'}
                        className="inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-sm)] border border-white/25 px-5 py-3 text-sm font-bold text-white"
                    >
                        {isEnglish ? 'Explore Curriculum' : 'Jelajahi Kurikulum'}
                    </Link>
                </div>
            </div>
        </div>
    );
}
