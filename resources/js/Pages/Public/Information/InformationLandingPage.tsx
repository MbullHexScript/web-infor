import {
    AlbumCard,
    AnnouncementCard,
    DocumentCard,
    EventCard,
    FaqAccordion,
    InformationHero,
    InformationShell,
    NewsGrid,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import {
    AlbumItem,
    AnnouncementItem,
    DocumentItem,
    EventItem,
    FaqItem,
    InformationPageProps,
    NewsItem,
} from '@/types/information';

export default function InformationLandingPage({
    locale,
    seo,
    news,
    announcements,
    events,
    albums,
    documents,
    faqs,
}: InformationPageProps & {
    news: NewsItem[];
    announcements: AnnouncementItem[];
    events: EventItem[];
    albums: AlbumItem[];
    documents: DocumentItem[];
    faqs: FaqItem[];
}) {
    const isEnglish = locale === 'en';
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero locale={locale} />
            <SectionBand title={isEnglish ? 'Latest News' : 'Berita Terbaru'}>
                <NewsGrid items={news} locale={locale} />
            </SectionBand>
            <SectionBand title={isEnglish ? 'Important Announcements' : 'Pengumuman Penting'}>
                <div className="grid gap-4 lg:grid-cols-2">
                    {announcements.map((item) => (
                        <AnnouncementCard key={item.id} item={item} locale={locale} />
                    ))}
                </div>
            </SectionBand>
            <SectionBand title={isEnglish ? 'Upcoming Events' : 'Agenda Terdekat'}>
                <div className="grid gap-4 lg:grid-cols-2">
                    {events.map((item) => (
                        <EventCard key={item.id} item={item} locale={locale} />
                    ))}
                </div>
            </SectionBand>
            <SectionBand title={isEnglish ? 'Gallery Albums' : 'Album Galeri'}>
                <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    {albums.map((item) => (
                        <AlbumCard key={item.id} item={item} />
                    ))}
                </div>
            </SectionBand>
            <SectionBand title={isEnglish ? 'Popular Documents' : 'Dokumen Populer'}>
                <div className="grid gap-4">
                    {documents.map((item) => (
                        <DocumentCard key={item.id} item={item} locale={locale} />
                    ))}
                </div>
            </SectionBand>
            <SectionBand title={isEnglish ? 'Featured FAQ' : 'FAQ Populer'}>
                <FaqAccordion items={faqs} />
            </SectionBand>
        </InformationShell>
    );
}
