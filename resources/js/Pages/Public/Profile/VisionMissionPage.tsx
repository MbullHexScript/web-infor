import {
    ProfileHero,
    ProfileShell,
    VisionMissionSection,
} from '@/Components/Profile/ProfileComponents';
import { ProfileContent, ProfilePageProps } from '@/types/profile';

export default function VisionMissionPage({
    locale,
    seo,
    page,
}: ProfilePageProps & { page: ProfileContent }) {
    return (
        <ProfileShell locale={locale} seo={seo}>
            <ProfileHero
                locale={locale}
                title={
                    locale === 'id' ? 'Visi, Misi, dan Tujuan' : 'Vision, Mission, and Objectives'
                }
                description={page.excerpt_id ?? ''}
            />
            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6">
                <VisionMissionSection page={page} locale={locale} />
            </div>
        </ProfileShell>
    );
}
