import {
    LecturerAcademicLinks,
    LecturerBiography,
    LecturerCourses,
    LecturerEducationTimeline,
    LecturerExpertise,
    LecturerProfileHeader,
    LecturerPublications,
    ProfileShell,
} from '@/Components/Profile/ProfileComponents';
import { Course } from '@/types/academics';
import { LecturerProfile, ProfilePageProps } from '@/types/profile';

export default function LecturerDetail({
    locale,
    seo,
    lecturer,
    courses,
}: ProfilePageProps & { lecturer: LecturerProfile; courses: Course[] }) {
    return (
        <ProfileShell
            locale={locale}
            seo={{ ...seo, title: `${lecturer.full_name} | Program Studi Informatika UIN Banten` }}
        >
            <div className="public-section mx-auto grid max-w-7xl gap-5 px-4 py-10 sm:px-6 lg:grid-cols-[320px_1fr]">
                <LecturerProfileHeader lecturer={lecturer} />
                <div className="grid gap-5">
                    <LecturerBiography lecturer={lecturer} locale={locale} />
                    <LecturerExpertise lecturer={lecturer} />
                    <LecturerAcademicLinks lecturer={lecturer} />
                    <LecturerEducationTimeline />
                    <LecturerCourses courses={courses} />
                    <LecturerPublications />
                </div>
            </div>
        </ProfileShell>
    );
}
