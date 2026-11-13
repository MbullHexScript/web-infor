import { HistoryTimeline, ProfileHero, ProfileShell } from '@/Components/Profile/ProfileComponents';
import { ProfileContent, ProfilePageProps } from '@/types/profile';

export default function HistoryPage({
    locale,
    seo,
    page,
}: ProfilePageProps & { page: ProfileContent }) {
    return (
        <ProfileShell locale={locale} seo={seo}>
            <ProfileHero
                locale={locale}
                title={locale === 'id' ? 'Sejarah Program Studi' : 'Study Program History'}
                description={page.excerpt_id ?? ''}
            />
            <div className="public-section mx-auto grid max-w-5xl gap-6 px-4 py-10 sm:px-6">
                <p className="leading-8 text-brand-graphite dark:text-slate-300">
                    {locale === 'en' ? page.content_en : page.content_id}
                </p>
                <HistoryTimeline locale={locale} />
            </div>
        </ProfileShell>
    );
}
