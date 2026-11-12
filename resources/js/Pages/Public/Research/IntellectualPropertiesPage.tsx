import {
    FilterBar,
    IpCard,
    Pagination,
    ResearchHero,
    ResearchShell,
    SectionBand,
} from '@/Components/Research/ResearchComponents';
import { Paginated } from '@/types/information';
import { IntellectualProperty, ResearchPageProps } from '@/types/research';

export default function IntellectualPropertiesPage({
    locale,
    seo,
    filters,
    items,
}: ResearchPageProps & {
    filters: Record<string, string>;
    items: Paginated<IntellectualProperty>;
}) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={locale === 'en' ? 'Intellectual Property' : 'Hak Kekayaan Intelektual'}
            />
            <SectionBand title="HKI">
                <FilterBar locale={locale} filters={filters} />
                <div className="grid gap-4 md:grid-cols-2">
                    {items.data.map((item) => (
                        <IpCard key={item.id} item={item} />
                    ))}
                </div>
                <Pagination data={items} />
            </SectionBand>
        </ResearchShell>
    );
}
