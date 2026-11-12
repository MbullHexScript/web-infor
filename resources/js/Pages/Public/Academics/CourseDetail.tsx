import {
    AcademicShell,
    CourseDetailHeader,
    CourseSoftwareList,
    RelatedCourses,
} from '@/Components/Academic/AcademicComponents';
import { AcademicPageProps, Course } from '@/types/academics';
import { Link } from '@inertiajs/react';

export default function CourseDetail({
    locale,
    seo,
    course,
}: AcademicPageProps & { course: Course }) {
    const isEnglish = locale === 'en';
    return (
        <AcademicShell
            locale={locale}
            title={seo?.title ?? course.name_id}
            description={seo?.description ?? ''}
        >
            <div className="mx-auto grid max-w-5xl gap-5 px-4 py-10 sm:px-6">
                <Link
                    href={`${locale === 'id' ? '/id/akademik/mata-kuliah' : '/en/academics/courses'}?semester=${course.semester}`}
                    className="text-sm font-bold text-brand-purple"
                >
                    {isEnglish ? 'Back to semester' : 'Kembali ke semester'} {course.semester}
                </Link>
                <CourseDetailHeader course={course} locale={locale} />
                <section className="rounded-md border border-brand-silver bg-white p-6 dark:border-slate-700 dark:bg-[#1c2128]">
                    <h2 className="text-xl font-black">{isEnglish ? 'Overview' : 'Ringkasan'}</h2>
                    <p className="mt-3 leading-7 text-brand-graphite dark:text-slate-300">
                        {isEnglish
                            ? course.description_en || course.description_id
                            : course.description_id}
                    </p>
                    <CourseSoftwareList course={course} />
                </section>
                <section className="rounded-md border border-brand-silver bg-white p-6 dark:border-slate-700 dark:bg-[#1c2128]">
                    <h2 className="text-xl font-black">
                        {isEnglish ? 'Dependent Courses' : 'Mata Kuliah Lanjutan'}
                    </h2>
                    <div className="mt-3">
                        <RelatedCourses courses={course.dependents} locale={locale} />
                    </div>
                </section>
            </div>
        </AcademicShell>
    );
}
