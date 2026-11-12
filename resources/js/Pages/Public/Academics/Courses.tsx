import {
    AcademicHero,
    AcademicShell,
    CourseMobileCard,
    CourseTable,
    SemesterSummary,
    SemesterTabs,
} from '@/Components/Academic/AcademicComponents';
import { AcademicPageProps, Course, Curriculum, SemesterTotal } from '@/types/academics';

export default function CoursesPage({
    locale,
    seo,
    curriculum,
    activeSemester,
    semesterTotals,
    coursesBySemester,
}: AcademicPageProps & {
    curriculum: Curriculum | null;
    activeSemester: number;
    semesterTotals: Record<string, SemesterTotal>;
    coursesBySemester: Record<string, Course[]>;
}) {
    const courses = coursesBySemester[String(activeSemester)] ?? [];
    const isEnglish = locale === 'en';
    return (
        <AcademicShell
            locale={locale}
            title={seo?.title ?? 'Struktur Mata Kuliah'}
            description={seo?.description ?? ''}
        >
            <AcademicHero
                locale={locale}
                eyebrow={curriculum?.code ?? 'OBE 2026'}
                title={isEnglish ? 'Course Structure' : 'Struktur Mata Kuliah'}
                description={
                    isEnglish
                        ? 'Browse the eight-semester course structure.'
                        : 'Telusuri struktur mata kuliah semester 1 sampai 8.'
                }
            />
            <div className="mx-auto grid max-w-7xl gap-5 px-4 py-10 sm:px-6">
                <SemesterTabs active={activeSemester} locale={locale} />
                <SemesterSummary
                    semester={activeSemester}
                    total={semesterTotals[String(activeSemester)]}
                />
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
