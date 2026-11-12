import {
    ScholarshipStatusBadge,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Locale, Scholarship, Seo } from '@/types/student-life';

export default function ScholarshipDetailPage({
    locale,
    seo,
    item,
}: {
    locale: Locale;
    seo: Seo;
    item: Scholarship;
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={item.name}
                description={item.description ?? undefined}
            />
            <SectionBand>
                <ScholarshipStatusBadge status={item.status} />
                <div className="mt-5 grid gap-4">
                    <p>
                        <strong>{locale === 'en' ? 'Provider' : 'Penyedia'}:</strong>{' '}
                        {item.provider ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Requirements' : 'Persyaratan'}:</strong>{' '}
                        {item.requirements ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Benefits' : 'Manfaat'}:</strong>{' '}
                        {item.benefits ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Period' : 'Periode'}:</strong>{' '}
                        {item.startsAt ?? '-'} - {item.endsAt ?? '-'}
                    </p>
                    {item.registrationUrl && (
                        <a
                            href={item.registrationUrl}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="w-fit rounded-md bg-brand-deep px-4 py-3 text-sm font-bold text-white"
                        >
                            {locale === 'en' ? 'Open official link' : 'Buka tautan resmi'}
                        </a>
                    )}
                    <p className="text-sm text-brand-graphite dark:text-slate-300">
                        {locale === 'en'
                            ? 'Applications are not processed on this website.'
                            : 'Pendaftaran beasiswa tidak diproses di website ini.'}
                    </p>
                </div>
            </SectionBand>
        </StudentLifeShell>
    );
}
