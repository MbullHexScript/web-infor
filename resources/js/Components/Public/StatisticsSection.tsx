import { Counter } from '@/Components/Public/Counter';
import { Locale, Statistic } from '@/types/homepage';

export function StatisticsSection({
    statistics,
    locale,
}: {
    statistics: Statistic[];
    locale: Locale;
}) {
    if (statistics.length === 0) return null;

    return (
        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            {statistics.map((statistic) => (
                <div
                    key={statistic.key}
                    className="rounded-[var(--radius-md)] border border-brand-silver bg-white p-5 text-center shadow-[var(--shadow-sm)] dark:border-slate-700 dark:bg-[#1c2128]"
                >
                    <div className="font-heading text-3xl font-bold text-brand-deep dark:text-brand-lavender">
                        <Counter value={statistic.value} suffix={statistic.suffix} />
                    </div>
                    <div className="mt-2 text-sm font-semibold text-brand-graphite dark:text-slate-300">
                        {locale === 'id' ? statistic.label_id : statistic.label_en}
                    </div>
                </div>
            ))}
        </div>
    );
}
