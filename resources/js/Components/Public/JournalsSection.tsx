import { Journal, Locale } from '@/types/homepage';
import { Link } from '@inertiajs/react';
import { ArrowRight, ExternalLink } from 'lucide-react';

const accents = ['border-brand-yellow', 'border-brand-red', 'border-brand-purple'];

export function JournalsSection({ journals, locale }: { journals: Journal[]; locale: Locale }) {
    const isEnglish = locale === 'en';

    return (
        <div className="grid gap-4 lg:grid-cols-3">
            {journals.map((journal, index) => (
                <article
                    key={journal.slug}
                    className={`rounded-[var(--radius-md)] border-t-4 ${accents[index % accents.length]} border-x border-b border-brand-silver bg-white p-5 shadow-[var(--shadow-sm)] dark:border-x-slate-700 dark:border-b-slate-700 dark:bg-[#1c2128]`}
                >
                    <div className="bg-brand-lavender/25 flex h-12 w-12 items-center justify-center rounded-full font-heading text-lg font-bold text-brand-deep dark:text-brand-lavender">
                        {journal.name.slice(0, 2)}
                    </div>
                    <h3 className="mt-5 text-xl font-bold text-brand-charcoal dark:text-white">
                        {journal.name}
                    </h3>
                    <p className="mt-1 text-sm font-semibold text-brand-deep dark:text-brand-lavender">
                        {journal.fullName}
                    </p>
                    <p className="mt-3 text-sm italic text-brand-graphite dark:text-slate-300">
                        {journal.tagline}
                    </p>
                    <p className="mt-4 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                        {isEnglish ? journal.scope_en : journal.scope_id}
                    </p>
                    <div className="bg-brand-lavender/20 mt-4 rounded-full px-3 py-1 text-xs font-bold text-brand-deep dark:text-brand-lavender">
                        {isEnglish
                            ? 'Publication information is being prepared.'
                            : 'Informasi penerbitan sedang dipersiapkan.'}
                    </div>
                    <div className="mt-5 flex flex-wrap gap-3">
                        {journal.ojsUrl && (
                            <a
                                href={journal.ojsUrl}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center gap-2 text-sm font-bold text-brand-deep dark:text-brand-lavender"
                            >
                                {isEnglish ? 'Visit Journal' : 'Kunjungi Jurnal'}
                                <ExternalLink size={16} />
                            </a>
                        )}
                        <Link
                            href={
                                isEnglish
                                    ? `/en/research/journals/${journal.slug}`
                                    : `/id/riset/rumah-jurnal/${journal.slug}`
                            }
                            className="inline-flex items-center gap-2 text-sm font-bold text-brand-deep dark:text-brand-lavender"
                        >
                            {isEnglish ? 'Learn More' : 'Pelajari Selengkapnya'}
                            <ArrowRight size={16} />
                        </Link>
                    </div>
                </article>
            ))}
        </div>
    );
}
