import { HomeContent, Locale } from '@/types/homepage';
import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';

export function HeadMessageSection({
    message,
    locale,
}: {
    message: HomeContent['headMessage'];
    locale: Locale;
}) {
    const isEnglish = locale === 'en';
    const displayName = [message.name, message.degree].filter(Boolean).join(', ');

    return (
        <div className="grid items-center gap-6 rounded-[var(--radius-lg)] border border-brand-silver bg-white p-6 shadow-[var(--shadow-sm)] dark:border-slate-700 dark:bg-[#1c2128] md:grid-cols-[14rem_1fr]">
            <div className="to-brand-yellow/30 relative aspect-[4/5] overflow-hidden rounded-[var(--radius-md)] bg-gradient-to-br from-brand-lavender">
                <img
                    src={message.photo ?? '/assets/people/kaprodi-hero.webp'}
                    alt={`${isEnglish ? 'Portrait of' : 'Foto'} ${displayName}`}
                    className="h-full w-full object-cover object-top transition duration-500 hover:scale-[1.03]"
                    loading="lazy"
                    decoding="async"
                />
            </div>
            <div>
                <blockquote className="text-xl font-semibold leading-9 text-brand-charcoal dark:text-white">
                    “{isEnglish ? message.quote_en : message.quote_id}”
                </blockquote>
                <div className="mt-5">
                    <div className="font-bold text-brand-charcoal dark:text-white">
                        {displayName}
                    </div>
                    <div className="text-sm text-brand-graphite dark:text-slate-300">
                        {isEnglish ? message.position_en : message.position_id}
                    </div>
                </div>
                <Link
                    href={isEnglish ? '/en/profile/head-message' : '/id/profil/sambutan'}
                    className="mt-5 inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-sm)] border border-brand-purple px-5 py-3 text-sm font-bold text-brand-deep dark:text-brand-lavender"
                >
                    {isEnglish ? 'Read Full Message' : 'Baca Selengkapnya'}
                    <ArrowRight size={18} />
                </Link>
            </div>
        </div>
    );
}
