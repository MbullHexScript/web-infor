import {
    AdmissionFaq,
    AdmissionPathCard,
    CareerRoadmap,
    Grid,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Achievement, AdmissionPath, Facility, Faq, Locale, Seo } from '@/types/student-life';

export default function AdmissionLandingPage({
    locale,
    seo,
    paths,
    faqs,
    portal,
}: {
    locale: Locale;
    seo: Seo;
    paths: AdmissionPath[];
    faqs: Faq[];
    facilities: Facility[];
    achievements: Achievement[];
    portal: { url?: string | null; note_id?: string; note_en?: string };
}) {
    const advantages =
        locale === 'en'
            ? [
                  'OBE curriculum',
                  'AI and Data',
                  'Software Engineering',
                  'Cybersecurity',
                  'Islamic values',
                  'Capstone and MBKM',
              ]
            : [
                  'Kurikulum OBE',
                  'AI dan Data',
                  'Rekayasa Perangkat Lunak',
                  'Cybersecurity',
                  'Nilai keislaman',
                  'Capstone dan MBKM',
              ];
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={
                    locale === 'en'
                        ? 'Join the Next Generation of Digital Innovators'
                        : 'Jadilah Bagian dari Generasi Digital Masa Depan'
                }
                description={
                    locale === 'en'
                        ? 'Discover an Informatics learning experience that integrates technology, innovation, and Islamic values.'
                        : 'Temukan pengalaman belajar Informatika yang memadukan teknologi, inovasi, dan nilai-nilai keislaman.'
                }
            />
            <SectionBand>
                <h2 className="text-2xl font-black">
                    {locale === 'en' ? 'Why Informatics?' : 'Mengapa Informatika?'}
                </h2>
                <div className="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    {advantages.map((item) => (
                        <div
                            key={item}
                            className="public-card rounded-md border border-brand-silver bg-white p-4 font-bold dark:border-slate-700 dark:bg-[#1c2128]"
                        >
                            {item}
                        </div>
                    ))}
                </div>
            </SectionBand>
            <SectionBand>
                <h2 className="text-2xl font-black">
                    {locale === 'en' ? 'Career Outlook' : 'Prospek Karier'}
                </h2>
                <div className="mt-5">
                    <CareerRoadmap locale={locale} />
                </div>
            </SectionBand>
            <SectionBand>
                <h2 className="text-2xl font-black">
                    {locale === 'en' ? 'Admission Pathways' : 'Jalur Penerimaan'}
                </h2>
                <div className="mt-5">
                    <Grid
                        items={paths}
                        empty={
                            locale === 'en'
                                ? 'Verified admission pathways are not yet published.'
                                : 'Jalur penerimaan terverifikasi belum dipublikasikan.'
                        }
                        render={(item) => (
                            <AdmissionPathCard key={item.id} item={item} locale={locale} />
                        )}
                    />
                </div>
                {portal.url ? (
                    <a
                        className="public-button-primary mt-5 inline-block rounded-md bg-brand-deep px-4 py-3 text-sm font-bold text-white"
                        href={portal.url}
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        {locale === 'en'
                            ? 'Open official admissions portal'
                            : 'Buka portal PMB resmi'}
                    </a>
                ) : (
                    <p className="mt-5 text-sm text-brand-graphite dark:text-slate-300">
                        {locale === 'en' ? portal.note_en : portal.note_id}
                    </p>
                )}
            </SectionBand>
            <SectionBand>
                <h2 className="text-2xl font-black">FAQ</h2>
                <div className="mt-5">
                    <AdmissionFaq items={faqs} />
                </div>
            </SectionBand>
        </StudentLifeShell>
    );
}
