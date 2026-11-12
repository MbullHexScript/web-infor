import {
    AchievementCard,
    FilterBar,
    Grid,
    Pagination,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Achievement, Locale, Paginator, Seo } from '@/types/student-life';

export default function AchievementsPage({
    locale,
    seo,
    items,
    filters,
    stats,
}: {
    locale: Locale;
    seo: Seo;
    items: Paginator<Achievement>;
    filters: Record<string, string>;
    stats: Record<string, number>;
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={locale === 'en' ? 'Achievements' : 'Prestasi'}
            />
            <SectionBand>
                <div className="grid gap-3 sm:grid-cols-3">
                    {Object.entries(stats).map(([key, value]) => (
                        <div
                            key={key}
                            className="rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]"
                        >
                            <div className="text-2xl font-black">{value}</div>
                            <div className="text-sm uppercase">{key}</div>
                        </div>
                    ))}
                </div>
                <div className="mt-6">
                    <FilterBar
                        filters={filters}
                        placeholder={locale === 'en' ? 'Search achievements' : 'Cari prestasi'}
                    />
                </div>
                <div className="mt-6">
                    <Grid
                        items={items.data}
                        empty={
                            locale === 'en'
                                ? 'No verified achievements found.'
                                : 'Prestasi terverifikasi tidak ditemukan.'
                        }
                        render={(item) => (
                            <AchievementCard key={item.id} item={item} locale={locale} />
                        )}
                    />
                </div>
                <Pagination links={items.links} />
            </SectionBand>
        </StudentLifeShell>
    );
}
