import { Announcement, Locale } from '@/types/homepage';
import { Link } from '@inertiajs/react';
import { ArrowRight, Megaphone, Pause, Play } from 'lucide-react';
import { useState } from 'react';

export function AnnouncementStrip({
    announcements,
    locale,
}: {
    announcements: Announcement[];
    locale: Locale;
}) {
    const [paused, setPaused] = useState(false);
    if (announcements.length === 0) return null;

    return (
        <section className="relative z-20 px-4 pt-8 text-brand-charcoal sm:px-6">
            <div className="bg-white/92 dark:bg-[#171b21]/92 mx-auto flex max-w-6xl flex-col gap-4 rounded-[1.1rem] border border-white/75 p-4 shadow-[0_24px_70px_rgba(23,27,33,.16)] backdrop-blur-2xl dark:border-white/10 dark:text-white lg:flex-row lg:items-center">
                <div className="flex items-center justify-between gap-4 lg:w-56">
                    <div className="flex items-center gap-3">
                        <div className="bg-brand-lavender/20 flex h-12 w-12 items-center justify-center rounded-[var(--radius-sm)] text-brand-deep dark:text-brand-lavender">
                            <Megaphone size={22} />
                        </div>
                        <div>
                            <div className="text-sm font-black text-brand-deep dark:text-brand-lavender">
                                {locale === 'id' ? 'PENGUMUMAN' : 'UPDATES'}
                            </div>
                            <div className="text-xs font-semibold text-brand-graphite dark:text-slate-300">
                                {locale === 'id' ? 'Terbaru' : 'Latest'}
                            </div>
                        </div>
                    </div>
                    <button
                        type="button"
                        className="inline-flex h-9 w-9 items-center justify-center rounded-[var(--radius-sm)] border border-brand-silver text-brand-deep transition hover:border-brand-purple dark:border-white/10 dark:text-brand-lavender"
                        onClick={() => setPaused((value) => !value)}
                        aria-label={paused ? 'Play announcements' : 'Pause announcements'}
                    >
                        {paused ? <Play size={16} /> : <Pause size={16} />}
                    </button>
                </div>
                <div
                    className={`grid flex-1 gap-3 md:grid-cols-3 ${
                        paused ? '' : 'motion-safe:animate-[soft-pulse_5s_ease-in-out_infinite]'
                    }`}
                >
                    {announcements.slice(0, 3).map((announcement) => (
                        <Link
                            key={announcement.title_id}
                            href={
                                locale === 'id'
                                    ? announcement.url
                                    : announcement.url.replace('/id/', '/en/')
                            }
                            className="hover:bg-brand-lavender/12 group rounded-[var(--radius-sm)] border-l border-brand-silver px-4 py-2 text-sm transition dark:border-white/10"
                        >
                            <span className="rounded-full bg-brand-yellow px-2 py-1 text-[10px] font-black uppercase text-brand-charcoal">
                                {announcement.priority}
                            </span>
                            <span className="mt-2 block font-bold leading-5 text-brand-charcoal dark:text-white">
                                {locale === 'id' ? announcement.title_id : announcement.title_en}
                            </span>
                            <span className="mt-1 inline-flex items-center gap-2 text-xs font-bold text-brand-deep dark:text-brand-lavender">
                                {locale === 'id' ? 'Lihat Informasi' : 'View Details'}
                                <ArrowRight
                                    size={13}
                                    className="transition group-hover:translate-x-1"
                                />
                            </span>
                        </Link>
                    ))}
                </div>
            </div>
        </section>
    );
}
