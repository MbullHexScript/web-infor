import {
    AchievementCard,
    CampusLifeSection,
    CompetitionCard,
    FacilityCard,
    Grid,
    HmpsExternalLink,
    ScholarshipCard,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import {
    Achievement,
    Alumni,
    Competition,
    Facility,
    Locale,
    Scholarship,
    Seo,
} from '@/types/student-life';
import { Link } from '@inertiajs/react';

export default function StudentLifeLandingPage({
    locale,
    seo,
    achievements,
    scholarships,
    competitions,
    facilities,
    hmps,
}: {
    locale: Locale;
    seo: Seo;
    achievements: Achievement[];
    scholarships: Scholarship[];
    competitions: Competition[];
    alumni: Alumni[];
    facilities: Facility[];
    hmps: { name: string; description: string | null; url: string };
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero locale={locale} />
            <CampusLifeSection
                locale={locale}
                content={
                    locale === 'en'
                        ? 'Student activities and documentation are curated from verified sources.'
                        : 'Aktivitas dan dokumentasi mahasiswa dikurasi dari sumber yang terverifikasi.'
                }
            />
            <SectionBand>
                <h2 className="text-2xl font-black">
                    {locale === 'en' ? 'Achievements' : 'Prestasi'}
                </h2>
                <div className="mt-5">
                    <Grid
                        items={achievements}
                        empty={
                            locale === 'en'
                                ? 'No verified achievements yet.'
                                : 'Prestasi terverifikasi belum tersedia.'
                        }
                        render={(item) => (
                            <AchievementCard key={item.id} item={item} locale={locale} />
                        )}
                    />
                </div>
            </SectionBand>
            <SectionBand>
                <h2 className="text-2xl font-black">
                    {locale === 'en' ? 'Scholarships and Competitions' : 'Beasiswa dan Kompetisi'}
                </h2>
                <div className="mt-5 grid gap-8 lg:grid-cols-2">
                    <Grid
                        items={scholarships}
                        empty={
                            locale === 'en'
                                ? 'No verified scholarships yet.'
                                : 'Beasiswa terverifikasi belum tersedia.'
                        }
                        render={(item) => (
                            <ScholarshipCard key={item.id} item={item} locale={locale} />
                        )}
                    />
                    <Grid
                        items={competitions}
                        empty={
                            locale === 'en'
                                ? 'No verified competitions yet.'
                                : 'Kompetisi terverifikasi belum tersedia.'
                        }
                        render={(item) => (
                            <CompetitionCard key={item.id} item={item} locale={locale} />
                        )}
                    />
                </div>
            </SectionBand>
            <SectionBand>
                <h2 className="text-2xl font-black">
                    {locale === 'en' ? 'Facilities' : 'Fasilitas'}
                </h2>
                <div className="mt-5">
                    <Grid
                        items={facilities}
                        empty={
                            locale === 'en'
                                ? 'No verified facilities yet.'
                                : 'Fasilitas terverifikasi belum tersedia.'
                        }
                        render={(item) => (
                            <FacilityCard key={item.id} item={item} locale={locale} />
                        )}
                    />
                </div>
            </SectionBand>
            <SectionBand>
                <h2 className="text-2xl font-black">{hmps.name}</h2>
                <p className="mt-2 max-w-3xl text-sm leading-7 text-brand-graphite dark:text-slate-300">
                    {hmps.description}
                </p>
                <div className="mt-5 flex flex-wrap gap-3">
                    <HmpsExternalLink url={hmps.url} locale={locale} />
                    <Link
                        href={locale === 'en' ? '/en/admissions' : '/id/pmb'}
                        className="rounded-md border border-brand-silver px-4 py-3 text-sm font-bold dark:border-slate-700"
                    >
                        {locale === 'en' ? 'Admissions' : 'Informasi PMB'}
                    </Link>
                </div>
            </SectionBand>
        </StudentLifeShell>
    );
}
