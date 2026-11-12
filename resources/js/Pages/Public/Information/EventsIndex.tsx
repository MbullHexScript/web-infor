import { EmptyState } from '@/Components/Public/EmptyState';
import {
    EventCard,
    FilterBar,
    InformationHero,
    InformationShell,
    ListingPage,
    Pagination,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import { EventItem, InformationPageProps, Paginated } from '@/types/information';

export default function EventsIndex({
    locale,
    seo,
    filters,
    events,
}: InformationPageProps & {
    filters: Record<string, string>;
    events: Paginated<EventItem>;
}) {
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero
                locale={locale}
                title={locale === 'en' ? 'Events' : 'Agenda'}
                description={
                    locale === 'en'
                        ? 'Upcoming, ongoing, and archived study program events.'
                        : 'Agenda mendatang, sedang berlangsung, dan arsip kegiatan program studi.'
                }
            />
            <SectionBand title={locale === 'en' ? 'Event List' : 'Daftar Agenda'}>
                <FilterBar locale={locale} filters={filters} />
                <ListingPage
                    items={events}
                    render={(item) => <EventCard key={item.id} item={item} locale={locale} />}
                    empty={<EmptyState title="Empty" description="No events found." />}
                />
                <Pagination data={events} />
            </SectionBand>
        </InformationShell>
    );
}
