import {
    FilterBar,
    Pagination,
    PublicationItem,
    ResearchHero,
    ResearchShell,
    SectionBand,
} from '@/Components/Research/ResearchComponents';
import { Paginated } from '@/types/information';
import { Publication, ResearchPageProps } from '@/types/research';

export default function PublicationsPage({
    locale,
    seo,
    filters,
    items,
}: ResearchPageProps & { filters: Record<string, string>; items: Paginated<Publication> }) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero locale={locale} title={locale === 'en' ? 'Publications' : 'Publikasi'} />
            <SectionBand title={locale === 'en' ? 'Repository' : 'Repositori Publikasi'}>
                <FilterBar locale={locale} filters={filters} />
                <div className="grid gap-4">
                    {items.data.map((item) => (
                        <PublicationItem key={item.id} item={item} />
                    ))}
                </div>
                <Pagination data={items} />
            </SectionBand>
        </ResearchShell>
    );
}
