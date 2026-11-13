import {
    JournalStatusBadge,
    ResearchHero,
    ResearchShell,
    ScopeList,
    SectionBand,
} from '@/Components/Research/ResearchComponents';
import { Journal, ResearchPageProps } from '@/types/research';

export default function JournalDetailPage({
    locale,
    seo,
    item,
}: ResearchPageProps & { item: Journal }) {
    const preparing = item.status === 'preparing';
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={item.name}
                description={item.tagline ?? undefined}
            />
            <SectionBand title={item.fullName ?? item.name}>
                <div className="public-card max-w-4xl rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
                    <JournalStatusBadge status={item.status} />
                    <p className="mt-4 leading-7 text-brand-graphite dark:text-slate-300">
                        {item.description}
                    </p>
                    {preparing && (
                        <p className="bg-brand-lavender/20 mt-4 rounded-md p-4 text-sm font-semibold">
                            {locale === 'en'
                                ? 'Publication information is being prepared. ISSN, editorial board, publication schedule, and OJS details will be updated when available.'
                                : 'Informasi penerbitan sedang dipersiapkan. Detail ISSN, dewan editor, jadwal terbit, dan tautan OJS akan diperbarui setelah tersedia.'}
                        </p>
                    )}
                    <dl className="mt-5 grid gap-3 text-sm sm:grid-cols-2">
                        <div>
                            <dt className="font-bold">Publisher</dt>
                            <dd>{item.publisher ?? '-'}</dd>
                        </div>
                        <div>
                            <dt className="font-bold">P-ISSN</dt>
                            <dd>{item.printIssn ?? '-'}</dd>
                        </div>
                        <div>
                            <dt className="font-bold">E-ISSN</dt>
                            <dd>{item.electronicIssn ?? '-'}</dd>
                        </div>
                        <div>
                            <dt className="font-bold">Editor</dt>
                            <dd>{item.editor ?? '-'}</dd>
                        </div>
                    </dl>
                    <div className="mt-5 flex flex-wrap gap-3">
                        {item.ojsUrl && (
                            <a
                                href={item.ojsUrl}
                                className="public-button-primary rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                            >
                                OJS
                            </a>
                        )}
                        {item.authorGuidelinesUrl && (
                            <a
                                href={item.authorGuidelinesUrl}
                                className="public-button-secondary rounded-md border px-4 py-2 text-sm font-bold"
                            >
                                Guidelines
                            </a>
                        )}
                        {item.articleTemplateUrl && (
                            <a
                                href={item.articleTemplateUrl}
                                className="public-button-secondary rounded-md border px-4 py-2 text-sm font-bold"
                            >
                                Template
                            </a>
                        )}
                    </div>
                </div>
            </SectionBand>
            <SectionBand title="Focus and Scope">
                <ScopeList items={item.scope} />
            </SectionBand>
        </ResearchShell>
    );
}
