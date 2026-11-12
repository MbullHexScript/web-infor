import {
    AcademicCalendarCard,
    AcademicHero,
    AcademicShell,
    EmptyState,
} from '@/Components/Academic/AcademicComponents';
import { AcademicPageProps } from '@/types/academics';

export default function AcademicCalendar({
    locale,
    seo,
    items,
}: AcademicPageProps & { items: Record<string, string>[] }) {
    return (
        <AcademicShell
            locale={locale}
            title={seo?.title ?? 'Kalender Akademik'}
            description={seo?.description ?? ''}
        >
            <AcademicHero
                locale={locale}
                eyebrow="Akademik"
                title={locale === 'id' ? 'Kalender Akademik' : 'Academic Calendar'}
                description={
                    locale === 'id'
                        ? 'Dokumen kalender akademik aktif.'
                        : 'Active academic calendar documents.'
                }
            />
            <div className="mx-auto grid max-w-7xl gap-4 px-4 py-10 sm:px-6">
                {items.length ? (
                    items.map((item) => <AcademicCalendarCard key={item.id} item={item} />)
                ) : (
                    <EmptyState title="Belum ada kalender akademik publik." />
                )}
            </div>
        </AcademicShell>
    );
}
