import { GraduateProfilesGrid } from '@/Components/Academic/AcademicComponents';
import { ProfileHero, ProfileShell } from '@/Components/Profile/ProfileComponents';
import { GraduateProfile } from '@/types/academics';
import { ProfilePageProps } from '@/types/profile';

export default function GraduateProfilesPage({
    locale,
    seo,
    profiles,
}: ProfilePageProps & { profiles: GraduateProfile[] }) {
    return (
        <ProfileShell locale={locale} seo={seo}>
            <ProfileHero
                locale={locale}
                title={locale === 'id' ? 'Profil Lulusan' : 'Graduate Profiles'}
                description={
                    locale === 'id'
                        ? 'Profil lulusan Kurikulum OBE 2026.'
                        : 'Graduate profiles from the 2026 OBE curriculum.'
                }
            />
            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6">
                <GraduateProfilesGrid profiles={profiles} locale={locale} />
            </div>
        </ProfileShell>
    );
}
