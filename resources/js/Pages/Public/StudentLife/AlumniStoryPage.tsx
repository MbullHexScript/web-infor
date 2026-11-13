import {
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Alumni, Locale, Seo } from '@/types/student-life';

export default function AlumniStoryPage({
    locale,
    seo,
    item,
}: {
    locale: Locale;
    seo: Seo;
    item: Alumni;
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={item.name}
                description={item.quote ?? undefined}
            />
            <SectionBand>
                <p className="public-prose max-w-3xl text-sm leading-7 text-brand-graphite dark:text-slate-300">
                    {item.story}
                </p>
                <div className="mt-5 grid gap-3 sm:grid-cols-2">
                    <p>
                        <strong>{locale === 'en' ? 'Graduation year' : 'Tahun lulus'}:</strong>{' '}
                        {item.graduationYear ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Position' : 'Posisi'}:</strong>{' '}
                        {item.position ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Company' : 'Perusahaan'}:</strong>{' '}
                        {item.company ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Industry' : 'Industri'}:</strong>{' '}
                        {item.industry ?? '-'}
                    </p>
                </div>
            </SectionBand>
        </StudentLifeShell>
    );
}
