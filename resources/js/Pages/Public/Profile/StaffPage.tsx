import { ProfileHero, ProfileShell, StaffGrid } from '@/Components/Profile/ProfileComponents';
import { LecturerProfile, ProfilePageProps } from '@/types/profile';

export default function StaffPage({
    locale,
    seo,
    staff,
}: ProfilePageProps & { staff: LecturerProfile[] }) {
    return (
        <ProfileShell locale={locale} seo={seo}>
            <ProfileHero
                locale={locale}
                title={locale === 'id' ? 'Tenaga Kependidikan' : 'Staff'}
                description={
                    locale === 'id'
                        ? 'Tenaga kependidikan Program Studi Informatika.'
                        : 'Administrative staff of the Informatics Study Program.'
                }
            />
            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6">
                <StaffGrid staff={staff} />
            </div>
        </ProfileShell>
    );
}
