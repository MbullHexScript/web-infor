import { Locale, QuickLink } from '@/types/homepage';
import {
    ArrowRight,
    BookOpen,
    CalendarCheck,
    CalendarDays,
    Download,
    ExternalLink,
    FileText,
    GraduationCap,
    LucideIcon,
} from 'lucide-react';

const icons: Record<string, LucideIcon> = {
    BookOpen,
    CalendarCheck,
    CalendarDays,
    Download,
    FileText,
    GraduationCap,
};

export function QuickLinksGrid({ links, locale }: { links: QuickLink[]; locale: Locale }) {
    return (
        <div className="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6">
            {links.map((link, index) => {
                const Icon = icons[link.icon ?? ''] ?? FileText;
                const href = localizeUrl(link.url, locale);
                return (
                    <a
                        key={link.key}
                        href={href}
                        target={link.isExternal ? '_blank' : undefined}
                        rel={link.isExternal ? 'noopener noreferrer' : undefined}
                        className="bg-white/88 group relative min-h-36 overflow-hidden rounded-[var(--radius-md)] border border-white/80 p-5 text-center shadow-[0_22px_60px_rgba(57,42,82,.12)] backdrop-blur-xl transition hover:-translate-y-1.5 hover:border-brand-purple hover:bg-white hover:shadow-[0_26px_70px_rgba(90,68,119,.22)] motion-safe:animate-[quick-card-rise_.72s_ease-out_both] dark:border-white/10 dark:bg-[#1c2128]"
                        style={{ animationDelay: `${index * 90}ms` }}
                    >
                        <div className="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-yellow via-brand-lavender to-brand-deep motion-safe:animate-[quick-flow_3.8s_linear_infinite]" />
                        <div className="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 bg-brand-yellow motion-safe:animate-[quick-side-scan_2.8s_ease-in-out_infinite]" />
                        <div className="bg-brand-lavender/20 group-hover:bg-brand-yellow/20 absolute -right-10 -top-10 h-24 w-24 rounded-full transition motion-safe:animate-[quick-orb_5s_ease-in-out_infinite]" />
                        <div className="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/65 to-transparent opacity-0 transition group-hover:opacity-100 motion-safe:group-hover:animate-[quick-shine_.9s_ease-out]" />
                        <div className="bg-brand-lavender/18 relative mx-auto flex h-14 w-14 items-center justify-center rounded-[var(--radius-sm)] text-brand-deep transition group-hover:scale-105 group-hover:bg-brand-deep group-hover:text-white motion-safe:animate-[quick-icon-float_4.2s_ease-in-out_infinite] dark:text-brand-lavender">
                            <Icon size={34} strokeWidth={1.8} />
                        </div>
                        <div className="relative mt-4 flex items-center justify-center gap-2 text-sm font-black text-brand-charcoal dark:text-white">
                            {locale === 'id' ? link.label_id : link.label_en}
                            {link.isExternal && <ExternalLink size={13} />}
                        </div>
                        <div className="relative mt-2 flex items-center justify-center gap-1 text-xs font-bold text-brand-deep opacity-0 transition group-hover:opacity-100 dark:text-brand-lavender">
                            {locale === 'id' ? 'Buka' : 'Open'}
                            <ArrowRight size={13} />
                        </div>
                    </a>
                );
            })}
        </div>
    );
}

function localizeUrl(url: string, locale: Locale) {
    if (url.startsWith('/id/')) return locale === 'en' ? url.replace('/id/', '/en/') : url;
    return url;
}
