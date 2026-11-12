import {
    AboutProgramSection,
    HeadMessageCard,
    ProfileHero,
    ProfileShell,
} from '@/Components/Profile/ProfileComponents';
import { ProfileContent, ProfilePageProps } from '@/types/profile';

type Summary = {
    about: ProfileContent;
    history: ProfileContent;
    vision: ProfileContent;
    headMessage: ProfileContent;
    lecturersCount: number;
    partnersCount: number;
};

export default function ProfileLandingPage({
    locale,
    seo,
    summary,
}: ProfilePageProps & { summary: Summary }) {
    return (
        <ProfileShell locale={locale} seo={seo}>
            <ProfileHero
                locale={locale}
                title={
                    locale === 'id'
                        ? 'Profil Program Studi Informatika'
                        : 'Informatics Study Program Profile'
                }
                description={
                    locale === 'id'
                        ? 'Mengenal lebih dekat Program Studi Informatika Fakultas Sains dan Teknologi UIN Sultan Maulana Hasanuddin Banten.'
                        : 'Discover the Informatics Study Program at the Faculty of Science and Technology, Sultan Maulana Hasanuddin State Islamic University Banten.'
                }
            />
            <div className="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6">
                <AboutProgramSection page={summary.about} locale={locale} />
                <HeadMessageCard page={summary.headMessage} locale={locale} />
                <div className="grid gap-4 md:grid-cols-3">
                    <Metric
                        label={locale === 'id' ? 'Dosen' : 'Lecturers'}
                        value={summary.lecturersCount}
                    />
                    <Metric
                        label={locale === 'id' ? 'Mitra' : 'Partners'}
                        value={summary.partnersCount}
                    />
                    <Metric label={locale === 'id' ? 'Kurikulum' : 'Curriculum'} value="OBE 2026" />
                </div>
            </div>
        </ProfileShell>
    );
}

function Metric({ label, value }: { label: string; value: string | number }) {
    return (
        <div className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="text-sm text-brand-graphite dark:text-slate-300">{label}</div>
            <div className="mt-1 text-3xl font-black">{value}</div>
        </div>
    );
}
