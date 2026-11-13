import {
    HmpsExternalLink,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Locale, Seo } from '@/types/student-life';

export default function HmpsPage({
    locale,
    seo,
    hmps,
}: {
    locale: Locale;
    seo: Seo;
    hmps: { name: string; description: string | null; url: string };
}) {
    const roles =
        locale === 'en'
            ? [
                  'Student aspiration',
                  'Leadership development',
                  'Academic activities',
                  'Collaboration',
              ]
            : [
                  'Aspirasi mahasiswa',
                  'Pengembangan kepemimpinan',
                  'Kegiatan akademik',
                  'Kolaborasi',
              ];
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={hmps.name}
                description={hmps.description ?? undefined}
            />
            <SectionBand>
                <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    {roles.map((role) => (
                        <div
                            key={role}
                            className="public-card rounded-md border border-brand-silver bg-white p-4 font-bold dark:border-slate-700 dark:bg-[#1c2128]"
                        >
                            {role}
                        </div>
                    ))}
                </div>
                <div className="mt-6">
                    <HmpsExternalLink url={hmps.url} locale={locale} />
                </div>
                <p className="mt-4 text-sm text-brand-graphite dark:text-slate-300">
                    {locale === 'en'
                        ? 'This page links to HMPS without copying or embedding the full external website.'
                        : 'Halaman ini menautkan HMPS tanpa menyalin atau meng-iframe penuh website eksternal.'}
                </p>
            </SectionBand>
        </StudentLifeShell>
    );
}
