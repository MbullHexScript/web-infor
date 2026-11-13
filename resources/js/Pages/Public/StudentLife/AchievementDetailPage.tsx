import {
    AchievementLevelBadge,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Achievement, Locale, Seo } from '@/types/student-life';

export default function AchievementDetailPage({
    locale,
    seo,
    item,
}: {
    locale: Locale;
    seo: Seo;
    item: Achievement;
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={item.title}
                description={item.description ?? undefined}
            />
            <SectionBand>
                <AchievementLevelBadge level={item.level} />
                <dl className="mt-5 grid gap-4 sm:grid-cols-2">
                    <Info
                        label={locale === 'en' ? 'Recipient' : 'Penerima'}
                        value={item.recipient}
                    />
                    <Info label={locale === 'en' ? 'Award' : 'Penghargaan'} value={item.award} />
                    <Info
                        label={locale === 'en' ? 'Organizer' : 'Penyelenggara'}
                        value={item.organizer}
                    />
                    <Info label={locale === 'en' ? 'Date' : 'Tanggal'} value={item.eventDate} />
                    <Info label={locale === 'en' ? 'Location' : 'Lokasi'} value={item.location} />
                    <Info
                        label={locale === 'en' ? 'Year' : 'Tahun'}
                        value={item.year?.toString()}
                    />
                </dl>
                {item.certificateUrl && (
                    <a
                        className="public-button-secondary mt-6 inline-block rounded-md border border-brand-silver px-4 py-3 text-sm font-bold dark:border-slate-700"
                        href={item.certificateUrl}
                    >
                        {locale === 'en' ? 'View certificate' : 'Lihat sertifikat'}
                    </a>
                )}
            </SectionBand>
        </StudentLifeShell>
    );
}

function Info({ label, value }: { label: string; value?: string | null }) {
    return (
        <div className="public-card rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]">
            <dt className="text-xs font-bold uppercase text-brand-purple">{label}</dt>
            <dd className="mt-1 font-semibold">{value ?? '-'}</dd>
        </div>
    );
}
