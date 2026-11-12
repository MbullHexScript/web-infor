import { EmptyState } from '@/Components/Public/EmptyState';
import { Announcement, Locale, LocalizedRecord } from '@/types/homepage';
import { CalendarDays } from 'lucide-react';

export function NewsInformationSection({
    locale,
    news,
    announcements,
    events,
}: {
    locale: Locale;
    news: LocalizedRecord[];
    announcements: Announcement[];
    events: LocalizedRecord[];
}) {
    const isEnglish = locale === 'en';

    return (
        <div className="grid gap-5 lg:grid-cols-[1.1fr_.9fr]">
            <div>
                {news.length === 0 ? (
                    <EmptyState
                        title={isEnglish ? 'News is being curated' : 'Berita sedang dikurasi'}
                        description={
                            isEnglish
                                ? 'Published news will appear after admin verification.'
                                : 'Berita published akan tampil setelah diverifikasi admin.'
                        }
                    />
                ) : null}
            </div>
            <div className="rounded-[var(--radius-md)] border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
                <div className="grid gap-4">
                    <div>
                        <h3 className="font-bold text-brand-charcoal dark:text-white">
                            {isEnglish ? 'Announcements' : 'Pengumuman'}
                        </h3>
                        <div className="mt-3 grid gap-3">
                            {announcements.length === 0 ? (
                                <p className="text-sm text-brand-graphite dark:text-slate-300">
                                    {isEnglish
                                        ? 'No active announcements.'
                                        : 'Belum ada pengumuman aktif.'}
                                </p>
                            ) : (
                                announcements.map((item) => (
                                    <div
                                        key={item.title_id}
                                        className="bg-brand-lavender/15 rounded-md p-3"
                                    >
                                        <div className="text-xs font-bold uppercase text-brand-red">
                                            {item.priority}
                                        </div>
                                        <div className="mt-1 text-sm font-semibold">
                                            {isEnglish ? item.title_en : item.title_id}
                                        </div>
                                    </div>
                                ))
                            )}
                        </div>
                    </div>
                    <div className="border-t border-brand-silver pt-4 dark:border-slate-700">
                        <h3 className="font-bold text-brand-charcoal dark:text-white">
                            {isEnglish ? 'Events' : 'Agenda'}
                        </h3>
                        {events.length === 0 && (
                            <div className="mt-3 flex items-center gap-2 text-sm text-brand-graphite dark:text-slate-300">
                                <CalendarDays size={16} />
                                {isEnglish
                                    ? 'No published events yet.'
                                    : 'Belum ada agenda published.'}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
