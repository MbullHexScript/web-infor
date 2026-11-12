import { Navbar } from '@/Components/Public/Navbar';
import { Course, GraduateProfile, SemesterTotal, Specialization } from '@/types/academics';
import { Locale } from '@/types/homepage';
import { Head, Link, router } from '@inertiajs/react';
import {
    BookOpen,
    CalendarDays,
    ChevronRight,
    Database,
    FileText,
    Filter,
    GraduationCap,
    Layers3,
    ShieldCheck,
} from 'lucide-react';
import { FormEvent, ReactNode } from 'react';

export function AcademicShell({
    locale,
    title,
    description,
    children,
}: {
    locale: Locale;
    title: string;
    description: string;
    children: ReactNode;
}) {
    return (
        <div className="min-h-screen overflow-x-hidden bg-brand-soft text-brand-charcoal dark:bg-[#11151a] dark:text-white">
            <Head title={title}>
                <meta name="description" content={description} />
            </Head>
            <Navbar locale={locale} hasTopBar={false} />
            <main id="content" className="pt-[4.75rem]">
                {children}
            </main>
            <footer className="border-t border-brand-silver bg-white py-8 text-sm text-brand-graphite dark:border-slate-700 dark:bg-[#1c2128] dark:text-slate-300">
                <div className="mx-auto max-w-7xl px-4 sm:px-6">
                    Program Studi Informatika UIN Sultan Maulana Hasanuddin Banten
                </div>
            </footer>
        </div>
    );
}

export function AcademicHero({
    locale,
    eyebrow,
    title,
    description,
}: {
    locale: Locale;
    eyebrow: string;
    title: string;
    description: string;
}) {
    return (
        <section className="border-brand-silver/70 border-b bg-white dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1.1fr_.9fr] lg:py-16">
                <div>
                    <div className="inline-flex items-center gap-2 rounded-md border border-brand-silver px-3 py-1 text-xs font-bold uppercase tracking-wide text-brand-purple dark:border-slate-600">
                        <GraduationCap size={14} />
                        {eyebrow}
                    </div>
                    <h1 className="mt-5 max-w-3xl text-4xl font-black leading-tight text-brand-deep dark:text-white md:text-5xl">
                        {title}
                    </h1>
                    <p className="mt-5 max-w-3xl text-base leading-8 text-brand-graphite dark:text-slate-300 md:text-lg">
                        {description}
                    </p>
                    <div className="mt-7 flex flex-wrap gap-3">
                        <Link
                            className="rounded-md bg-brand-deep px-5 py-3 text-sm font-bold text-white"
                            href={
                                locale === 'id'
                                    ? '/id/akademik/mata-kuliah'
                                    : '/en/academics/courses'
                            }
                        >
                            {locale === 'id' ? 'Lihat Mata Kuliah' : 'View Courses'}
                        </Link>
                        <Link
                            className="rounded-md border border-brand-silver px-5 py-3 text-sm font-bold text-brand-deep dark:text-white"
                            href={
                                locale === 'id' ? '/id/akademik/rps' : '/en/academics/course-plans'
                            }
                        >
                            {locale === 'id' ? 'Repositori RPS' : 'Course Plans'}
                        </Link>
                    </div>
                </div>
                <div className="grid content-end gap-3 sm:grid-cols-3 lg:grid-cols-1">
                    {[
                        ['144', locale === 'id' ? 'SKS paket mahasiswa' : 'student credits'],
                        ['8', locale === 'id' ? 'semester' : 'semesters'],
                        ['3', locale === 'id' ? 'peminatan' : 'tracks'],
                    ].map(([value, label]) => (
                        <div
                            key={label}
                            className="rounded-md border border-brand-silver bg-brand-soft p-5 dark:border-slate-700 dark:bg-[#11151a]"
                        >
                            <div className="text-3xl font-black text-brand-deep dark:text-brand-yellow">
                                {value}
                            </div>
                            <div className="mt-1 text-sm text-brand-graphite dark:text-slate-300">
                                {label}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}

export function CurriculumStats({ totals }: { totals: Record<string, SemesterTotal> }) {
    return (
        <div className="grid gap-3 md:grid-cols-4">
            {Object.entries(totals).map(([semester, total]) => (
                <div
                    key={semester}
                    className="rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]"
                >
                    <div className="text-sm font-semibold text-brand-purple">
                        Semester {semester}
                    </div>
                    <div className="mt-2 text-2xl font-black">
                        {total.student_package_credits} SKS
                    </div>
                    <div className="mt-1 text-xs text-brand-graphite dark:text-slate-300">
                        {total.course_count} MK, {total.elective_credits} SKS pilihan ditawarkan
                    </div>
                </div>
            ))}
        </div>
    );
}

export function CurriculumPhilosophy({ locale }: { locale: Locale }) {
    return (
        <section className="rounded-md border border-brand-silver bg-white p-6 dark:border-slate-700 dark:bg-[#1c2128]">
            <h2 className="text-2xl font-black">
                {locale === 'id' ? 'Filosofi OBE' : 'OBE Philosophy'}
            </h2>
            <p className="mt-3 leading-7 text-brand-graphite dark:text-slate-300">
                {locale === 'id'
                    ? 'Kurikulum disusun dari capaian lulusan, kompetensi inti, pengalaman belajar berbasis proyek, capstone, magang atau MBKM, KUKERTA/KKN, dan tugas akhir.'
                    : 'The curriculum is organized from graduate outcomes, core competencies, project-based learning, capstone, internship or MBKM, community service, and final project.'}
            </p>
        </section>
    );
}

export function GraduateProfilesGrid({
    profiles,
    locale,
}: {
    profiles: GraduateProfile[];
    locale: Locale;
}) {
    return (
        <div className="grid gap-4 md:grid-cols-2">
            {profiles.map((profile) => (
                <GraduateProfileCard key={profile.code} profile={profile} locale={locale} />
            ))}
        </div>
    );
}

export function GraduateProfileCard({
    profile,
    locale,
}: {
    profile: GraduateProfile;
    locale: Locale;
}) {
    return (
        <article className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="font-mono text-sm font-bold text-brand-purple">{profile.code}</div>
            <h3 className="mt-2 text-xl font-black">
                {locale === 'en' ? profile.title_en || profile.title_id : profile.title_id}
            </h3>
            <p className="mt-3 line-clamp-4 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                {profile.description_id}
            </p>
            <p className="mt-3 text-xs leading-5 text-brand-graphite dark:text-slate-400">
                {profile.career_examples_id}
            </p>
        </article>
    );
}

export function SpecializationsGrid({
    specializations,
    locale,
}: {
    specializations: Specialization[];
    locale: Locale;
}) {
    return (
        <div className="grid gap-4 lg:grid-cols-3">
            {specializations.map((specialization) => (
                <SpecializationCard
                    key={specialization.code}
                    specialization={specialization}
                    locale={locale}
                />
            ))}
        </div>
    );
}

export function SpecializationCard({
    specialization,
    locale,
}: {
    specialization: Specialization;
    locale: Locale;
}) {
    const href =
        locale === 'id'
            ? `/id/akademik/peminatan/${specialization.slug_id}`
            : `/en/academics/specializations/${specialization.slug_en}`;
    return (
        <article className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="flex items-center justify-between">
                <div className="font-mono text-sm font-bold text-brand-purple">
                    {specialization.code}
                </div>
                <IconFor code={specialization.code} />
            </div>
            <h3 className="mt-3 text-xl font-black">
                {locale === 'en'
                    ? specialization.name_en || specialization.name_id
                    : specialization.name_id}
            </h3>
            <p className="mt-3 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                {specialization.description_id}
            </p>
            <Link
                href={href}
                className="mt-5 inline-flex items-center gap-2 text-sm font-bold text-brand-deep dark:text-brand-yellow"
            >
                {locale === 'id' ? 'Lihat alur' : 'View track'} <ChevronRight size={16} />
            </Link>
        </article>
    );
}

export function CurriculumJourney({ locale }: { locale: Locale }) {
    const items =
        locale === 'id'
            ? [
                  'Fondasi Informatika',
                  'Penguatan Kompetensi',
                  'Peminatan',
                  'Capstone dan MBKM',
                  'KUKERTA/KKN',
                  'Tugas Akhir',
              ]
            : [
                  'Informatics Foundation',
                  'Competency Building',
                  'Specialization',
                  'Capstone and MBKM',
                  'Community Service',
                  'Final Project',
              ];
    return (
        <div className="grid gap-3 md:grid-cols-3">
            {items.map((item, index) => (
                <div
                    key={item}
                    className="rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]"
                >
                    <div className="text-xs font-bold text-brand-purple">0{index + 1}</div>
                    <div className="mt-1 font-bold">{item}</div>
                </div>
            ))}
        </div>
    );
}

export function SemesterTabs({ active, locale }: { active: number; locale: Locale }) {
    return (
        <div role="tablist" aria-label="Semester" className="flex gap-2 overflow-x-auto pb-2">
            {Array.from({ length: 8 }, (_, index) => index + 1).map((semester) => (
                <Link
                    key={semester}
                    role="tab"
                    aria-selected={active === semester}
                    href={`${locale === 'id' ? '/id/akademik/mata-kuliah' : '/en/academics/courses'}?semester=${semester}`}
                    className={`h-11 min-w-24 rounded-md border px-4 text-sm font-bold leading-10 ${active === semester ? 'border-brand-deep bg-brand-deep text-white' : 'border-brand-silver bg-white text-brand-graphite dark:border-slate-700 dark:bg-[#1c2128] dark:text-slate-200'}`}
                >
                    Semester {semester}
                </Link>
            ))}
        </div>
    );
}

export function SemesterSummary({ semester, total }: { semester: number; total?: SemesterTotal }) {
    return (
        <div className="rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="text-sm font-semibold text-brand-graphite dark:text-slate-300">
                Total Semester {semester}
            </div>
            <div className="mt-1 text-3xl font-black">
                {total?.student_package_credits ?? 0} SKS
            </div>
        </div>
    );
}

export function CourseTable({ courses, locale }: { courses: Course[]; locale: Locale }) {
    return (
        <div className="hidden overflow-hidden rounded-md border border-brand-silver bg-white dark:border-slate-700 dark:bg-[#1c2128] lg:block">
            <table className="w-full text-left text-sm">
                <caption className="sr-only">Struktur mata kuliah</caption>
                <thead className="bg-brand-soft text-xs uppercase text-brand-graphite dark:bg-[#11151a] dark:text-slate-300">
                    <tr>
                        <th className="px-4 py-3">Kode</th>
                        <th className="px-4 py-3">Mata Kuliah</th>
                        <th className="px-4 py-3">SKS</th>
                        <th className="px-4 py-3">Jenis</th>
                        <th className="px-4 py-3">Kelompok</th>
                        <th className="px-4 py-3">Prasyarat</th>
                        <th className="px-4 py-3">RPS</th>
                    </tr>
                </thead>
                <tbody>
                    {courses.map((course) => (
                        <CourseTableRow key={course.code} course={course} locale={locale} />
                    ))}
                </tbody>
            </table>
        </div>
    );
}

export function CourseTableRow({ course, locale }: { course: Course; locale: Locale }) {
    return (
        <tr className="border-t border-brand-silver dark:border-slate-700">
            <td className="px-4 py-4 font-mono font-bold">{course.code}</td>
            <td className="px-4 py-4">
                <Link
                    className="font-bold text-brand-deep dark:text-brand-yellow"
                    href={courseHref(course, locale)}
                >
                    {locale === 'en' ? course.name_en || course.name_id : course.name_id}
                </Link>
                {course.name_en && (
                    <div className="text-xs text-brand-graphite dark:text-slate-400">
                        {course.name_en}
                    </div>
                )}
            </td>
            <td className="px-4 py-4 font-bold">{course.credits}</td>
            <td className="px-4 py-4">
                <CourseTypeBadge type={course.course_type} locale={locale} />
            </td>
            <td className="px-4 py-4">{course.category}</td>
            <td className="px-4 py-4">
                <CoursePrerequisiteList course={course} />
            </td>
            <td className="px-4 py-4 text-brand-graphite dark:text-slate-300">-</td>
        </tr>
    );
}

export function CourseMobileCard({ course, locale }: { course: Course; locale: Locale }) {
    return (
        <article className="rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="flex items-start justify-between gap-3">
                <div>
                    <div className="font-mono text-sm font-bold text-brand-purple">
                        {course.code}
                    </div>
                    <h3 className="mt-1 font-black">
                        {locale === 'en' ? course.name_en || course.name_id : course.name_id}
                    </h3>
                </div>
                <div className="rounded-md bg-brand-soft px-3 py-1 text-sm font-black dark:bg-[#11151a]">
                    {course.credits} SKS
                </div>
            </div>
            <div className="mt-3 flex flex-wrap gap-2 text-xs">
                <CourseTypeBadge type={course.course_type} locale={locale} />
                <span className="rounded-md bg-brand-soft px-2 py-1 dark:bg-[#11151a]">
                    Semester {course.semester}
                </span>
                {course.specialization_name_id && (
                    <span className="rounded-md bg-brand-soft px-2 py-1 dark:bg-[#11151a]">
                        {course.specialization_name_id}
                    </span>
                )}
            </div>
            <div className="mt-4 flex gap-2">
                <Link
                    href={courseHref(course, locale)}
                    className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                >
                    Detail
                </Link>
                <Link
                    href={locale === 'id' ? '/id/akademik/rps' : '/en/academics/course-plans'}
                    className="rounded-md border border-brand-silver px-4 py-2 text-sm font-bold"
                >
                    RPS
                </Link>
            </div>
        </article>
    );
}

export function CourseTypeBadge({ type, locale }: { type: Course['course_type']; locale: Locale }) {
    return (
        <span className="bg-brand-lavender/25 rounded-md px-2 py-1 text-xs font-bold text-brand-deep dark:text-brand-yellow">
            {type === 'mandatory'
                ? locale === 'id'
                    ? 'Wajib'
                    : 'Mandatory'
                : locale === 'id'
                  ? 'Pilihan'
                  : 'Elective'}
        </span>
    );
}

export function CoursePrerequisiteList({ course }: { course: Course }) {
    const text = String(course.metadata?.prerequisite_text ?? '-');
    return <span className="text-xs text-brand-graphite dark:text-slate-300">{text}</span>;
}

export function CourseDetailHeader({ course, locale }: { course: Course; locale: Locale }) {
    return (
        <div className="rounded-md border border-brand-silver bg-white p-6 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="font-mono text-sm font-bold text-brand-purple">{course.code}</div>
            <h1 className="mt-2 text-3xl font-black">
                {locale === 'en' ? course.name_en || course.name_id : course.name_id}
            </h1>
            <CourseMetadata course={course} locale={locale} />
        </div>
    );
}

export function CourseMetadata({ course, locale }: { course: Course; locale: Locale }) {
    return (
        <div className="mt-4 flex flex-wrap gap-2 text-sm">
            <span className="rounded-md bg-brand-soft px-3 py-1 dark:bg-[#11151a]">
                Semester {course.semester}
            </span>
            <span className="rounded-md bg-brand-soft px-3 py-1 dark:bg-[#11151a]">
                {course.credits} SKS
            </span>
            <CourseTypeBadge type={course.course_type} locale={locale} />
            {course.category && (
                <span className="rounded-md bg-brand-soft px-3 py-1 dark:bg-[#11151a]">
                    {course.category}
                </span>
            )}
        </div>
    );
}

export function CourseSoftwareList({ course }: { course: Course }) {
    if (!course.software_tools_id) return null;
    return (
        <p className="mt-2 text-sm text-brand-graphite dark:text-slate-300">
            {course.software_tools_id}
        </p>
    );
}

export function RelatedCourses({ courses, locale }: { courses?: Course[]; locale: Locale }) {
    if (!courses?.length)
        return (
            <p className="text-sm text-brand-graphite dark:text-slate-300">
                Belum ada mata kuliah lanjutan yang terdata.
            </p>
        );
    return (
        <div className="grid gap-2">
            {courses.map((course) => (
                <Link
                    key={course.code}
                    href={courseHref(course, locale)}
                    className="rounded-md border border-brand-silver p-3 text-sm font-bold dark:border-slate-700"
                >
                    {course.code} - {course.name_id}
                </Link>
            ))}
        </div>
    );
}

export function RpsFilters({
    filters,
    locale,
}: {
    filters: Record<string, string>;
    locale: Locale;
}) {
    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        const data = new FormData(event.currentTarget);
        router.get(
            locale === 'id' ? '/id/akademik/rps' : '/en/academics/course-plans',
            Object.fromEntries(data.entries()),
            { preserveState: true },
        );
    }
    return (
        <form
            onSubmit={submit}
            className="rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]"
        >
            <div className="grid gap-3 md:grid-cols-[1fr_140px_160px_auto]">
                <label className="grid gap-1 text-sm font-bold">
                    <span>{locale === 'id' ? 'Cari' : 'Search'}</span>
                    <input
                        name="q"
                        defaultValue={filters.q ?? ''}
                        className="rounded-md border-brand-silver dark:bg-[#11151a]"
                        placeholder="Kode atau nama MK"
                    />
                </label>
                <label className="grid gap-1 text-sm font-bold">
                    <span>Semester</span>
                    <select
                        name="semester"
                        defaultValue={filters.semester ?? ''}
                        className="rounded-md border-brand-silver dark:bg-[#11151a]"
                    >
                        <option value="">Semua</option>
                        {Array.from({ length: 8 }, (_, i) => (
                            <option key={i + 1}>{i + 1}</option>
                        ))}
                    </select>
                </label>
                <label className="grid gap-1 text-sm font-bold">
                    <span>Jenis</span>
                    <select
                        name="type"
                        defaultValue={filters.type ?? ''}
                        className="rounded-md border-brand-silver dark:bg-[#11151a]"
                    >
                        <option value="">Semua</option>
                        <option value="mandatory">Wajib</option>
                        <option value="elective">Pilihan</option>
                    </select>
                </label>
                <button className="mt-6 inline-flex h-11 items-center justify-center gap-2 rounded-md bg-brand-deep px-4 text-sm font-bold text-white">
                    <Filter size={16} /> Filter
                </button>
            </div>
        </form>
    );
}

export function RpsRepository({ children }: { children: ReactNode }) {
    return <div className="grid gap-4">{children}</div>;
}

export function RpsTable({ items, locale }: { items: Course[]; locale: Locale }) {
    return <CourseTable courses={items} locale={locale} />;
}

export function RpsMobileCard({ course, locale }: { course: Course; locale: Locale }) {
    return <CourseMobileCard course={course} locale={locale} />;
}

export function RpsMetadata({ rps }: { rps: Record<string, unknown> }) {
    return (
        <pre className="whitespace-pre-wrap rounded-md bg-brand-soft p-4 text-xs dark:bg-[#11151a]">
            {JSON.stringify(rps, null, 2)}
        </pre>
    );
}

export function PdfPreview({ src }: { src?: string }) {
    if (!src) return <PdfErrorFallback />;
    return (
        <iframe
            title="PDF preview"
            src={src}
            sandbox=""
            className="h-[70vh] w-full rounded-md border border-brand-silver dark:border-slate-700"
        />
    );
}

export function PdfErrorFallback() {
    return (
        <div className="rounded-md border border-brand-silver bg-white p-6 text-sm text-brand-graphite dark:border-slate-700 dark:bg-[#1c2128] dark:text-slate-300">
            Preview PDF belum tersedia. Gunakan tombol download jika file sudah dipublikasikan.
        </div>
    );
}

export function AcademicCalendarCard({ item }: { item: Record<string, string> }) {
    return (
        <DocumentCard
            icon={<CalendarDays size={18} />}
            title={item.title_id ?? 'Kalender Akademik'}
        />
    );
}

export function ClassScheduleCard({ item }: { item: Record<string, string> }) {
    return <DocumentCard icon={<Layers3 size={18} />} title={item.title_id ?? 'Jadwal Kuliah'} />;
}

export function AcademicDocumentCard({ item }: { item: Record<string, string> }) {
    return (
        <DocumentCard icon={<FileText size={18} />} title={item.title_id ?? 'Dokumen Akademik'} />
    );
}

export function EmptyState({ title }: { title: string }) {
    return (
        <div className="rounded-md border border-dashed border-brand-silver bg-white p-8 text-center text-brand-graphite dark:border-slate-700 dark:bg-[#1c2128] dark:text-slate-300">
            {title}
        </div>
    );
}

export function ImportWizard({ children }: { children: ReactNode }) {
    return <div className="grid gap-4">{children}</div>;
}
export function ImportFileStep({ children }: { children: ReactNode }) {
    return (
        <section className="rounded-md bg-white p-5 shadow-sm dark:bg-[#1c2128]">
            {children}
        </section>
    );
}
export function ImportMappingStep() {
    return <EmptyState title="Mapping mengikuti header dinamis workbook." />;
}
export function ImportValidationStep() {
    return <EmptyState title="Validasi ditampilkan pada laporan import." />;
}
export function ImportPreviewStep() {
    return <EmptyState title="Dry-run tersedia sebelum commit." />;
}
export function ImportResultStep() {
    return <EmptyState title="Hasil import tersimpan di storage/logs/imports." />;
}
export function ImportWarningTable({ warnings }: { warnings?: string[] }) {
    return (
        <div>
            {warnings?.map((warning) => (
                <p key={warning}>{warning}</p>
            ))}
        </div>
    );
}
export function CurriculumValidationDashboard({ children }: { children: ReactNode }) {
    return <div className="grid gap-4 md:grid-cols-3">{children}</div>;
}

function DocumentCard({ icon, title }: { icon: ReactNode; title: string }) {
    return (
        <article className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="flex items-center gap-2 font-bold">
                {icon}
                {title}
            </div>
        </article>
    );
}

function IconFor({ code }: { code: string }) {
    if (code === 'PM02') return <Database className="text-brand-purple" size={22} />;
    if (code === 'PM03') return <ShieldCheck className="text-brand-purple" size={22} />;
    return <BookOpen className="text-brand-purple" size={22} />;
}

function courseHref(course: Course, locale: Locale) {
    return locale === 'id'
        ? `/id/akademik/mata-kuliah/${course.slug_id}`
        : `/en/academics/courses/${course.slug_en}`;
}
