import { EmptyState } from '@/Components/Public/EmptyState';
import {
    AlbumCard,
    FilterBar,
    InformationHero,
    InformationShell,
    Pagination,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import { AlbumItem, InformationPageProps, Paginated } from '@/types/information';

export default function GalleryIndex({
    locale,
    seo,
    filters,
    albums,
}: InformationPageProps & {
    filters: Record<string, string>;
    albums: Paginated<AlbumItem>;
}) {
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero locale={locale} title={locale === 'en' ? 'Gallery' : 'Galeri'} />
            <SectionBand title={locale === 'en' ? 'Albums' : 'Album'}>
                <FilterBar locale={locale} filters={filters} />
                {albums.data.length === 0 ? (
                    <EmptyState title="Empty" description="No public albums found." />
                ) : (
                    <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                        {albums.data.map((item) => (
                            <AlbumCard key={item.id} item={item} />
                        ))}
                    </div>
                )}
                <Pagination data={albums} />
            </SectionBand>
        </InformationShell>
    );
}
