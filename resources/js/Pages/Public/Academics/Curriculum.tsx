import {
    AcademicHero,
    AcademicShell,
    CurriculumJourney,
    CurriculumPhilosophy,
    CurriculumStats,
    GraduateProfilesGrid,
    SpecializationsGrid,
} from '@/Components/Academic/AcademicComponents';
import {
    AcademicPageProps,
    Curriculum,
    GraduateProfile,
    SemesterTotal,
    Specialization,
} from '@/types/academics';

export default function CurriculumPage({
    locale,
    seo,
    curriculum,
    semesterTotals,
    specializations,
    profiles,
}: AcademicPageProps & {
    curriculum: Curriculum | null;
    semesterTotals: Record<string, SemesterTotal>;
    specializations: Specialization[];
    profiles: GraduateProfile[];
}) {
    const isEnglish = locale === 'en';
    return (
        <AcademicShell
            locale={locale}
            title={seo?.title ?? 'Kurikulum OBE 2026'}
            description={seo?.description ?? ''}
        >
            <AcademicHero
                locale={locale}
                eyebrow={curriculum?.code ?? 'OBE 2026'}
                title={isEnglish ? 'OBE Curriculum 2026' : 'Kurikulum OBE 2026'}
                description={
                    isEnglish
                        ? 'A curriculum designed to develop professional competence, innovation capability, integrity, and responsibility in responding to digital transformation.'
                        : 'Kurikulum yang dirancang untuk membangun kompetensi profesional, kemampuan inovasi, integritas, dan tanggung jawab dalam menghadapi transformasi digital.'
                }
            />
            <div className="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6">
                <CurriculumStats totals={semesterTotals} />
                <CurriculumPhilosophy locale={locale} />
                <section>
                    <h2 className="mb-4 text-2xl font-black">
                        {isEnglish ? 'Graduate Profiles' : 'Profil Lulusan'}
                    </h2>
                    <GraduateProfilesGrid profiles={profiles} locale={locale} />
                </section>
                <section>
                    <h2 className="mb-4 text-2xl font-black">
                        {isEnglish ? 'Specializations' : 'Peminatan'}
                    </h2>
                    <SpecializationsGrid specializations={specializations} locale={locale} />
                </section>
                <section>
                    <h2 className="mb-4 text-2xl font-black">
                        {isEnglish ? 'Learning Journey' : 'Alur Pembelajaran'}
                    </h2>
                    <CurriculumJourney locale={locale} />
                </section>
            </div>
        </AcademicShell>
    );
}
