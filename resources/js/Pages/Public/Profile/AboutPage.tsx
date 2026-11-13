import {
    AboutProgramSection,
    ProfileHero,
    ProfileShell,
} from '@/Components/Profile/ProfileComponents';
import { ProfileContent, ProfilePageProps } from '@/types/profile';

export default function AboutPage({
    locale,
    seo,
    page,
}: ProfilePageProps & { page: ProfileContent }) {
    return (
        <ProfileShell locale={locale} seo={seo}>
            <ProfileHero
                locale={locale}
                title={locale === 'id' ? 'Tentang Informatika' : 'About Informatics'}
                description={page.excerpt_id ?? ''}
            />
            <div className="public-section mx-auto max-w-5xl px-4 py-10 sm:px-6">
                <AboutProgramSection page={page} locale={locale} />
            </div>
        </ProfileShell>
    );
}
