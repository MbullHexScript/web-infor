import {
    FilterBar,
    Pagination,
    ResearchHero,
    ResearchShell,
    SectionBand,
    ServiceCard,
} from '@/Components/Research/ResearchComponents';
import { Paginated } from '@/types/information';
import { CommunityService, ResearchPageProps } from '@/types/research';

export default function CommunityServicesPage({
    locale,
    seo,
    filters,
    items,
}: ResearchPageProps & { filters: Record<string, string>; items: Paginated<CommunityService> }) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={locale === 'en' ? 'Community Service' : 'Pengabdian kepada Masyarakat'}
            />
            <SectionBand title={locale === 'en' ? 'Activities' : 'Kegiatan'}>
                <FilterBar locale={locale} filters={filters} />
                <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    {items.data.map((item) => (
                        <ServiceCard key={item.id} item={item} locale={locale} />
                    ))}
                </div>
                <Pagination data={items} />
            </SectionBand>
        </ResearchShell>
    );
}
