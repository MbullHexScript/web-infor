import { Locale, HomePageProps } from '@/types/homepage';
import { Link } from '@inertiajs/react';
import { ArrowRight, FileText } from 'lucide-react';

export function CurriculumSection({
    curriculum,
    locale,
}: {
    curriculum: HomePageProps['curriculum'];
    locale: Locale;
}) {
    const isEnglish = locale === 'en';

    return (
        <div className="rounded-[var(--radius-lg)] bg-brand-charcoal p-6 text-white shadow-[var(--shadow-lg)] dark:bg-[#1c2128] md:p-8">
            <div className="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
                <div>
                    <p className="text-sm font-bold uppercase tracking-[0.16em] text-brand-yellow">
                        {isEnglish ? 'OBE 2026 Curriculum' : 'Kurikulum OBE 2026'}
                    </p>
                    <h2 className="mt-3 font-heading text-3xl font-bold tracking-normal md:text-4xl">
                        {isEnglish
                            ? 'A clear academic journey across eight semesters'
                            : 'Perjalanan akademik yang terstruktur dalam delapan semester'}
                    </h2>
                    <div className="mt-6 grid grid-cols-3 gap-3">
                        <Metric
                            value={`${curriculum.total_sks}`}
                            label={isEnglish ? 'Credits' : 'SKS'}
                        />
                        <Metric
                            value={`${curriculum.semesters}`}
                            label={isEnglish ? 'Semesters' : 'Semester'}
                        />
                        <Metric
                            value={`${curriculum.specializations.length}`}
                            label={isEnglish ? 'Tracks' : 'Peminatan'}
                        />
                    </div>
                    <div className="mt-6 flex flex-wrap gap-3">
                        <Link
                            href={isEnglish ? '/en/academics/courses' : '/id/akademik/mata-kuliah'}
                            className="inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-sm)] bg-brand-yellow px-5 py-3 text-sm font-bold text-brand-charcoal"
                        >
                            {isEnglish ? 'View Course Structure' : 'Lihat Struktur Mata Kuliah'}
                            <ArrowRight size={18} />
                        </Link>
                        <Link
                            href={isEnglish ? '/en/academics/course-plans' : '/id/akademik/rps'}
                            className="inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-sm)] border border-white/25 px-5 py-3 text-sm font-bold text-white"
                        >
                            <FileText size={18} />
                            {isEnglish ? 'Find Course Plans' : 'Cari RPS'}
                        </Link>
                    </div>
                </div>
                <div>
                    <div className="flex gap-3 overflow-x-auto pb-3 lg:grid lg:grid-cols-4 lg:overflow-visible">
                        {Array.from({ length: 8 }).map((_, index) => (
                            <div
                                key={index}
                                className="min-w-32 rounded-[var(--radius-md)] border border-white/15 bg-white/10 p-4 text-center"
                            >
                                <div className="font-mono text-xs text-brand-yellow">
                                    {isEnglish ? 'Semester' : 'Semester'}
                                </div>
                                <div className="mt-2 font-heading text-3xl font-bold">
                                    {index + 1}
                                </div>
                            </div>
                        ))}
                    </div>
                    <div className="mt-5 grid gap-3 sm:grid-cols-3">
                        {curriculum.specializations.map((track) => (
                            <div
                                key={track.name_id}
                                className="rounded-[var(--radius-sm)] bg-white/10 p-3 text-sm font-semibold"
                            >
                                {isEnglish ? track.name_en : track.name_id}
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}

function Metric({ value, label }: { value: string; label: string }) {
    return (
        <div className="rounded-[var(--radius-sm)] border border-white/15 bg-white/10 p-4">
            <div className="font-heading text-3xl font-bold">{value}</div>
            <div className="mt-1 text-xs font-semibold uppercase tracking-wide text-white/75">
                {label}
            </div>
        </div>
    );
}
