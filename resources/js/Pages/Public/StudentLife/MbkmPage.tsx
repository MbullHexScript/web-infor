import {
    AdmissionFaq,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Faq, Locale, Seo } from '@/types/student-life';

export default function MbkmPage({
    locale,
    seo,
    page,
    faqs,
}: {
    locale: Locale;
    seo: Seo;
    page: { title: string; content: string } | null;
    faqs: Faq[];
}) {
    const programs =
        locale === 'en'
            ? [
                  'Internship',
                  'Independent Study',
                  'Student Exchange',
                  'Research',
                  'Entrepreneurship',
              ]
            : ['Magang', 'Studi Independen', 'Pertukaran Mahasiswa', 'Penelitian', 'Kewirausahaan'];
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={page?.title ?? 'MBKM'}
                description={page?.content}
            />
            <SectionBand>
                <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    {programs.map((program) => (
                        <div
                            key={program}
                            className="public-card rounded-md border border-brand-silver bg-white p-4 font-bold dark:border-slate-700 dark:bg-[#1c2128]"
                        >
                            {program}
                        </div>
                    ))}
                </div>
                <p className="mt-6 text-sm leading-7 text-brand-graphite dark:text-slate-300">
                    {locale === 'en'
                        ? 'Official procedures and requirements must be completed by admins based on university policy.'
                        : 'Prosedur dan persyaratan resmi wajib dilengkapi admin berdasarkan kebijakan universitas.'}
                </p>
            </SectionBand>
            <SectionBand>
                <AdmissionFaq items={faqs} />
            </SectionBand>
        </StudentLifeShell>
    );
}
