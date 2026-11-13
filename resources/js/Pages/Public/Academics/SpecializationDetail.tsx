import {
    AcademicShell,
    CourseMobileCard,
    CourseTable,
    SpecializationCard,
} from '@/Components/Academic/AcademicComponents';
import { AcademicPageProps, Course, Specialization } from '@/types/academics';

export default function SpecializationDetail({
    locale,
    seo,
    specialization,
    courses,
}: AcademicPageProps & { specialization: Specialization; courses: Course[] }) {
    return (
        <AcademicShell
            locale={locale}
            title={seo?.title ?? specialization.name_id}
            description={seo?.description ?? ''}
        >
            <div className="public-section mx-auto grid max-w-7xl gap-5 px-4 py-10 sm:px-6">
                <SpecializationCard specialization={specialization} locale={locale} />
                <CourseTable courses={courses} locale={locale} />
                <div className="grid gap-3 lg:hidden">
                    {courses.map((course) => (
                        <CourseMobileCard key={course.code} course={course} locale={locale} />
                    ))}
                </div>
            </div>
        </AcademicShell>
    );
}
