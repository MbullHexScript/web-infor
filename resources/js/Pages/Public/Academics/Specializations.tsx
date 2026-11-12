import {
    AcademicHero,
    AcademicShell,
    SpecializationsGrid,
} from '@/Components/Academic/AcademicComponents';
import { AcademicPageProps, Curriculum, Specialization } from '@/types/academics';

export default function Specializations({
    locale,
    seo,
    curriculum,
    specializations,
}: AcademicPageProps & { curriculum: Curriculum | null; specializations: Specialization[] }) {
    const isEnglish = locale === 'en';
    return (
        <AcademicShell
            locale={locale}
            title={seo?.title ?? 'Peminatan'}
            description={seo?.description ?? ''}
        >
            <AcademicHero
                locale={locale}
                eyebrow={curriculum?.code ?? 'OBE 2026'}
                title={isEnglish ? 'Specializations' : 'Peminatan'}
                description={
                    isEnglish
                        ? 'Choose one focused track in semesters 5 to 7.'
                        : 'Mahasiswa memilih satu jalur pendalaman pada semester 5 sampai 7.'
                }
            />
            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6">
                <SpecializationsGrid specializations={specializations} locale={locale} />
            </div>
        </AcademicShell>
    );
}
