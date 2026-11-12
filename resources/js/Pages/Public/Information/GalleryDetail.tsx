import {
    GalleryIconLabel,
    InformationHero,
    InformationShell,
    MediaLightbox,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import { AlbumItem, InformationPageProps, MediaItem } from '@/types/information';

export default function GalleryDetail({
    locale,
    seo,
    album,
    media,
}: InformationPageProps & {
    album: AlbumItem;
    media: MediaItem[];
}) {
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero
                locale={locale}
                title={album.title}
                description={album.description ?? undefined}
            />
            <SectionBand title={locale === 'en' ? 'Media' : 'Media'}>
                <div className="mb-5">
                    <GalleryIconLabel label={`${media.length} media`} />
                </div>
                <MediaLightbox media={media} />
            </SectionBand>
        </InformationShell>
    );
}
