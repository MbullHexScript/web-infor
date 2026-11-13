import {
    CompetitionStatusBadge,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Competition, Locale, Seo } from '@/types/student-life';

export default function CompetitionDetailPage({
    locale,
    seo,
    item,
}: {
    locale: Locale;
    seo: Seo;
    item: Competition;
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={item.title}
                description={item.description ?? undefined}
            />
            <SectionBand>
                <CompetitionStatusBadge status={item.status} />
                <dl className="mt-5 grid gap-4 sm:grid-cols-2">
                    <Info
                        label={locale === 'en' ? 'Organizer' : 'Penyelenggara'}
                        value={item.organizer}
                    />
                    <Info label={locale === 'en' ? 'Level' : 'Tingkat'} value={item.level} />
                    <Info
                        label={locale === 'en' ? 'Event date' : 'Tanggal'}
                        value={item.eventDate}
                    />
                    <Info label={locale === 'en' ? 'Location' : 'Lokasi'} value={item.location} />
                    <Info
                        label={locale === 'en' ? 'Registration deadline' : 'Batas daftar'}
                        value={item.endsAt}
                    />
                </dl>
                {item.registrationUrl && (
                    <a
                        href={item.registrationUrl}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="public-button-primary mt-6 inline-block rounded-md bg-brand-deep px-4 py-3 text-sm font-bold text-white"
                    >
                        {locale === 'en' ? 'Open registration' : 'Buka pendaftaran'}
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
