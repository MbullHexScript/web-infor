import {
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Facility, Locale, Seo } from '@/types/student-life';

export default function FacilityDetailPage({
    locale,
    seo,
    item,
}: {
    locale: Locale;
    seo: Seo;
    item: Facility;
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={item.name}
                description={item.description ?? undefined}
            />
            <SectionBand>
                <div className="grid gap-4 sm:grid-cols-2">
                    <p>
                        <strong>{locale === 'en' ? 'Category' : 'Kategori'}:</strong>{' '}
                        {item.category ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Location' : 'Lokasi'}:</strong>{' '}
                        {item.location ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Capacity' : 'Kapasitas'}:</strong>{' '}
                        {item.capacity ?? '-'}
                    </p>
                </div>
                <ul className="mt-5 grid gap-2 sm:grid-cols-2">
                    {item.features.map((feature) => (
                        <li
                            key={feature}
                            className="rounded-md border border-brand-silver p-3 text-sm dark:border-slate-700"
                        >
                            {feature}
                        </li>
                    ))}
                </ul>
            </SectionBand>
        </StudentLifeShell>
    );
}
