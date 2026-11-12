import { EmptyState } from '@/Components/Public/EmptyState';
import { ResponsiveImage } from '@/Components/Public/ResponsiveImage';
import { Lecturer, Locale } from '@/types/homepage';
import { Link } from '@inertiajs/react';

export function LecturersSection({ lecturers, locale }: { lecturers: Lecturer[]; locale: Locale }) {
    if (lecturers.length === 0) {
        return (
            <EmptyState
                title={
                    locale === 'id'
                        ? 'Profil dosen menunggu verifikasi'
                        : 'Lecturer profiles await verification'
                }
                description={
                    locale === 'id'
                        ? 'Dosen aktif dan terverifikasi akan ditampilkan setelah data disetujui admin.'
                        : 'Active and verified lecturers will appear after admin approval.'
                }
            />
        );
    }

    return (
        <div className="grid gap-4 md:grid-cols-3">
            {lecturers.map((lecturer) => (
                <article
                    key={lecturer.slug ?? lecturer.name}
                    className="rounded-[var(--radius-md)] border border-brand-silver bg-white p-4 shadow-[var(--shadow-sm)] dark:border-slate-700 dark:bg-[#1c2128]"
                >
                    {lecturer.photo ? (
                        <ResponsiveImage
                            src={lecturer.photo}
                            alt={lecturer.name}
                            width={400}
                            height={500}
                            className="aspect-[4/5] w-full rounded-[var(--radius-sm)] object-cover"
                        />
                    ) : (
                        <div className="bg-brand-lavender/30 flex aspect-[4/5] w-full items-center justify-center rounded-[var(--radius-sm)] text-4xl font-bold text-brand-deep">
                            {lecturer.name
                                .split(' ')
                                .slice(0, 2)
                                .map((part) => part[0])
                                .join('')}
                        </div>
                    )}
                    <h3 className="mt-4 text-lg font-bold text-brand-charcoal dark:text-white">
                        {lecturer.name}
                    </h3>
                    {lecturer.degree && (
                        <p className="text-sm text-brand-graphite dark:text-slate-300">
                            {lecturer.degree}
                        </p>
                    )}
                    <p className="mt-3 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                        {locale === 'id' ? lecturer.expertise_id : lecturer.expertise_en}
                    </p>
                    <Link
                        href={
                            locale === 'id'
                                ? `/id/profil/dosen/${lecturer.slug}`
                                : `/en/profile/lecturers/${lecturer.slug}`
                        }
                        className="mt-4 inline-flex text-sm font-bold text-brand-deep dark:text-brand-lavender"
                    >
                        {locale === 'id' ? 'Lihat Profil' : 'View Profile'}
                    </Link>
                </article>
            ))}
        </div>
    );
}
