import {
    AnnouncementPriorityBadge,
    ArticleContent,
    ExternalSafeLink,
    InformationHero,
    InformationShell,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import { AnnouncementItem, InformationPageProps } from '@/types/information';

export default function AnnouncementDetail({
    locale,
    seo,
    announcement,
}: InformationPageProps & {
    announcement: AnnouncementItem;
}) {
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero
                locale={locale}
                title={announcement.title}
                description={announcement.excerpt ?? undefined}
            />
            <SectionBand title={locale === 'en' ? 'Announcement' : 'Pengumuman'}>
                <div className="mb-5 flex flex-wrap gap-2">
                    <AnnouncementPriorityBadge priority={announcement.priority} />
                    {announcement.expired && (
                        <span className="rounded-md bg-gray-100 px-3 py-2 text-sm font-bold text-gray-700">
                            {locale === 'en'
                                ? 'This announcement has expired.'
                                : 'Pengumuman ini telah berakhir.'}
                        </span>
                    )}
                </div>
                <ArticleContent html={announcement.content} />
                <div className="mt-6 flex flex-wrap gap-3">
                    {announcement.attachmentUrl && (
                        <a
                            href={announcement.attachmentUrl}
                            className="public-button-primary rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                        >
                            {locale === 'en' ? 'Download attachment' : 'Unduh lampiran'}
                        </a>
                    )}
                    <ExternalSafeLink href={announcement.externalUrl}>
                        {locale === 'en' ? 'Open source' : 'Buka tautan'}
                    </ExternalSafeLink>
                </div>
            </SectionBand>
        </InformationShell>
    );
}
