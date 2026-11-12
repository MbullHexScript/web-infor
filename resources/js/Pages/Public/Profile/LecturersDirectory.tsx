import {
    LecturerCard,
    LecturerFilters,
    ProfileHero,
    ProfileShell,
} from '@/Components/Profile/ProfileComponents';
import { LecturerProfile, ProfilePageProps } from '@/types/profile';

export default function LecturersDirectory({
    locale,
    seo,
    lecturers,
    filters,
}: ProfilePageProps & { lecturers: { data: LecturerProfile[] }; filters: Record<string, string> }) {
    return (
        <ProfileShell locale={locale} seo={seo}>
            <ProfileHero
                locale={locale}
                title={locale === 'id' ? 'Dosen Informatika' : 'Informatics Lecturers'}
                description={
                    locale === 'id'
                        ? 'Direktori dosen Program Studi Informatika.'
                        : 'Lecturer directory of the Informatics Study Program.'
                }
            />
            <div className="mx-auto grid max-w-7xl gap-5 px-4 py-10 sm:px-6">
                <LecturerFilters filters={filters} locale={locale} />
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {lecturers.data.map((lecturer) => (
                        <LecturerCard key={lecturer.id} lecturer={lecturer} locale={locale} />
                    ))}
                </div>
            </div>
        </ProfileShell>
    );
}
