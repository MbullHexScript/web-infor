import {
    ArticleContent,
    EventStatusBadge,
    ExternalSafeLink,
    InformationHero,
    InformationShell,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import { EventItem, InformationPageProps } from '@/types/information';

export default function EventDetail({
    locale,
    seo,
    event,
}: InformationPageProps & {
    event: EventItem;
}) {
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero
                locale={locale}
                title={event.title}
                description={event.location ?? undefined}
            />
            <SectionBand title={locale === 'en' ? 'Event Details' : 'Detail Agenda'}>
                <div className="public-card mb-5 grid gap-3 rounded-md border border-brand-silver bg-white p-5 text-sm dark:border-slate-700 dark:bg-[#1c2128]">
                    <EventStatusBadge status={event.status} />
                    <div>
                        {event.startAt} - {event.endAt} ({event.timezone})
                    </div>
                    <div>{event.location}</div>
                    <div>{event.organizer}</div>
                </div>
                <ArticleContent html={event.description} />
                <div className="mt-6 flex flex-wrap gap-3">
                    <a
                        href={event.icsUrl}
                        className="public-button-primary rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                    >
                        ICS
                    </a>
                    <ExternalSafeLink href={event.googleCalendarUrl}>
                        Google Calendar
                    </ExternalSafeLink>
                    <ExternalSafeLink href={event.registrationUrl}>
                        {locale === 'en' ? 'Register' : 'Daftar'}
                    </ExternalSafeLink>
                    <ExternalSafeLink href={event.meetingUrl}>
                        {locale === 'en' ? 'Meeting' : 'Ruang meeting'}
                    </ExternalSafeLink>
                </div>
            </SectionBand>
        </InformationShell>
    );
}
