import { ResearchHero, ResearchShell, SectionBand } from '@/Components/Research/ResearchComponents';
import { ResearchPageProps } from '@/types/research';

export default function ResearchPartnershipsPage({
    locale,
    seo,
    items,
}: ResearchPageProps & { items: Array<Record<string, string | number | null>> }) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={locale === 'en' ? 'Research Partnerships' : 'Kerja Sama Riset'}
            />
            <SectionBand title={locale === 'en' ? 'Partners' : 'Mitra'}>
                <div className="grid gap-4 md:grid-cols-2">
                    {items.length === 0 ? (
                        <p className="text-sm text-brand-graphite dark:text-slate-300">
                            {locale === 'en'
                                ? 'Verified research partnerships will appear here.'
                                : 'Kerja sama riset terverifikasi akan tampil di sini.'}
                        </p>
                    ) : (
                        items.map((item) => (
                            <article
                                key={String(item.id)}
                                className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]"
                            >
                                <h3 className="font-black">{item.title}</h3>
                                <p className="mt-2 text-sm">{item.scope}</p>
                            </article>
                        ))
                    )}
                </div>
            </SectionBand>
        </ResearchShell>
    );
}
