import {
    AcademicHero,
    AcademicShell,
    EmptyState,
    RpsFilters,
    RpsMobileCard,
    RpsTable,
} from '@/Components/Academic/AcademicComponents';
import { AcademicPageProps, Course, Curriculum } from '@/types/academics';

export default function RpsRepositoryPage({
    locale,
    seo,
    curriculum,
    filters,
    items,
}: AcademicPageProps & {
    curriculum: Curriculum | null;
    filters: Record<string, string>;
    items: { data: Course[]; total: number };
}) {
    const isEnglish = locale === 'en';
    return (
        <AcademicShell
            locale={locale}
            title={seo?.title ?? 'Repositori RPS'}
            description={seo?.description ?? ''}
        >
            <AcademicHero
                locale={locale}
                eyebrow={curriculum?.code ?? 'OBE 2026'}
                title={isEnglish ? 'Course Plan Repository' : 'Repositori RPS'}
                description={
                    isEnglish
                        ? 'Find and download semester course plans for courses offered by the Informatics Study Program.'
                        : 'Temukan dan unduh Rencana Pembelajaran Semester mata kuliah Program Studi Informatika.'
                }
            />
            <div className="mx-auto grid max-w-7xl gap-5 px-4 py-10 sm:px-6">
                <RpsFilters filters={filters} locale={locale} />
                <div className="text-sm font-bold text-brand-graphite dark:text-slate-300">
                    {items.total} hasil
                </div>
                {items.data.length ? (
                    <>
                        <RpsTable items={items.data} locale={locale} />
                        <div className="grid gap-3 lg:hidden">
                            {items.data.map((course) => (
                                <RpsMobileCard key={course.code} course={course} locale={locale} />
                            ))}
                        </div>
                    </>
                ) : (
                    <EmptyState
                        title={
                            isEnglish
                                ? 'No public course plans match the filter.'
                                : 'Belum ada RPS publik yang cocok dengan filter.'
                        }
                    />
                )}
            </div>
        </AcademicShell>
    );
}
