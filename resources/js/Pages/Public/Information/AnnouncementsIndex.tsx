import { EmptyState } from '@/Components/Public/EmptyState';
import {
    AnnouncementCard,
    FilterBar,
    InformationHero,
    InformationShell,
    ListingPage,
    Pagination,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import { AnnouncementItem, InformationPageProps, Paginated } from '@/types/information';

export default function AnnouncementsIndex({
    locale,
    seo,
    filters,
    announcements,
}: InformationPageProps & {
    filters: Record<string, string>;
    announcements: Paginated<AnnouncementItem>;
}) {
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero
                locale={locale}
                title={locale === 'en' ? 'Announcements' : 'Pengumuman'}
            />
            <SectionBand title={locale === 'en' ? 'Active Announcements' : 'Pengumuman Aktif'}>
                <FilterBar locale={locale} filters={filters} />
                <ListingPage
                    items={announcements}
                    render={(item) => (
                        <AnnouncementCard key={item.id} item={item} locale={locale} />
                    )}
                    empty={<EmptyState title="Empty" description="No announcements found." />}
                />
                <Pagination data={announcements} />
            </SectionBand>
        </InformationShell>
    );
}
