import {
    PublicationItem,
    ResearchCard,
    ResearchHero,
    ResearchShell,
    ScopeList,
    SectionBand,
} from '@/Components/Research/ResearchComponents';
import { ExpertiseGroup, Publication, ResearchPageProps, ResearchProject } from '@/types/research';

export default function ExpertiseGroupDetailPage({
    locale,
    seo,
    item,
    research,
    publications,
}: ResearchPageProps & {
    item: ExpertiseGroup;
    research: ResearchProject[];
    publications: Publication[];
}) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={item.name}
                description={item.description ?? undefined}
            />
            <SectionBand title={locale === 'en' ? 'Research Focus' : 'Fokus Riset'}>
                <ScopeList items={(item.focus ?? '').split('\n').filter(Boolean)} />
            </SectionBand>
            <SectionBand title={locale === 'en' ? 'Related Research' : 'Penelitian Terkait'}>
                <div className="grid gap-5 md:grid-cols-3">
                    {research.map((row) => (
                        <ResearchCard key={row.id} item={row} locale={locale} />
                    ))}
                </div>
            </SectionBand>
            <SectionBand title={locale === 'en' ? 'Related Publications' : 'Publikasi Terkait'}>
                <div className="grid gap-4">
                    {publications.map((row) => (
                        <PublicationItem key={row.id} item={row} />
                    ))}
                </div>
            </SectionBand>
        </ResearchShell>
    );
}
