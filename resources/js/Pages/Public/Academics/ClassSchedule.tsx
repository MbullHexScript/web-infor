import {
    AcademicHero,
    AcademicShell,
    ClassScheduleCard,
    EmptyState,
} from '@/Components/Academic/AcademicComponents';
import { AcademicPageProps } from '@/types/academics';

export default function ClassSchedule({
    locale,
    seo,
    items,
}: AcademicPageProps & { items: Record<string, string>[] }) {
    return (
        <AcademicShell
            locale={locale}
            title={seo?.title ?? 'Jadwal Kuliah'}
            description={seo?.description ?? ''}
        >
            <AcademicHero
                locale={locale}
                eyebrow="Akademik"
                title={locale === 'id' ? 'Jadwal Kuliah' : 'Class Schedule'}
                description={
                    locale === 'id'
                        ? 'Dokumen jadwal perkuliahan aktif.'
                        : 'Active class schedule documents.'
                }
            />
            <div className="public-section mx-auto grid max-w-7xl gap-4 px-4 py-10 sm:px-6">
                {items.length ? (
                    items.map((item) => <ClassScheduleCard key={item.id} item={item} />)
                ) : (
                    <EmptyState title="Belum ada jadwal publik." />
                )}
            </div>
        </AcademicShell>
    );
}
