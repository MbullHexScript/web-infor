import {
    FilterBar,
    Pagination,
    ResearchCard,
    ResearchHero,
    ResearchShell,
    SectionBand,
} from '@/Components/Research/ResearchComponents';
import { Paginated } from '@/types/information';
import { ResearchPageProps, ResearchProject } from '@/types/research';

export default function ResearchProjectsPage({
    locale,
    seo,
    filters,
    groups,
    items,
}: ResearchPageProps & {
    filters: Record<string, string>;
    groups: Array<{ slug: string; name: string }>;
    items: Paginated<ResearchProject>;
}) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={locale === 'en' ? 'Research Projects' : 'Penelitian Dosen'}
            />
            <SectionBand title={locale === 'en' ? 'Projects' : 'Penelitian'}>
                <FilterBar locale={locale} filters={filters} categories={groups} />
                <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    {items.data.map((item) => (
                        <ResearchCard key={item.id} item={item} locale={locale} />
                    ))}
                </div>
                <Pagination data={items} />
            </SectionBand>
        </ResearchShell>
    );
}
