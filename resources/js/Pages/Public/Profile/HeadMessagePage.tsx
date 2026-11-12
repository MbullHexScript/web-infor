import { HeadMessageCard, ProfileHero, ProfileShell } from '@/Components/Profile/ProfileComponents';
import { ProfileContent, ProfilePageProps } from '@/types/profile';

export default function HeadMessagePage({
    locale,
    seo,
    page,
}: ProfilePageProps & { page: ProfileContent }) {
    return (
        <ProfileShell locale={locale} seo={seo}>
            <ProfileHero
                locale={locale}
                title={
                    locale === 'id'
                        ? 'Sambutan Ketua Program Studi'
                        : 'Head of Study Program Message'
                }
                description={page.excerpt_id ?? ''}
            />
            <div className="mx-auto max-w-5xl px-4 py-10 sm:px-6">
                <HeadMessageCard page={page} locale={locale} />
            </div>
        </ProfileShell>
    );
}
