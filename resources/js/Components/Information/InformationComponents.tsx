import { EmptyState } from '@/Components/Public/EmptyState';
import { Footer } from '@/Components/Public/Footer';
import { Navbar } from '@/Components/Public/Navbar';
import { TopBar } from '@/Components/Public/TopBar';
import {
    AlbumItem,
    AnnouncementItem,
    DocumentItem,
    EventItem,
    FaqItem,
    MediaItem,
    NewsItem,
    Paginated,
    SearchResult,
    Seo,
} from '@/types/information';
import { Locale } from '@/types/homepage';
import { Head, Link, router } from '@inertiajs/react';
import {
    AlertTriangle,
    ArrowRight,
    CalendarDays,
    CheckCircle2,
    Download,
    ExternalLink,
    FileText,
    ImageIcon,
    Search,
    X,
} from 'lucide-react';
import { FormEvent, ReactNode, useEffect, useState } from 'react';

const topLinks = [
    { label: 'UIN SMH Banten', url: 'https://uinbanten.ac.id/', external: true },
    { label: 'FST', url: 'https://fsainsuinbanten.my.id/', external: true },
    { label: 'SIAKAD', url: 'https://siakad.uinbanten.ac.id/', external: true },
];

const quickLinks = [
    {
        key: 'news',
        label_id: 'Berita',
        label_en: 'News',
        url: '/id/informasi/berita',
        isExternal: false,
        sortOrder: 1,
    },
    {
        key: 'downloads',
        label_id: 'Unduhan',
        label_en: 'Downloads',
        url: '/id/informasi/unduhan',
        isExternal: false,
        sortOrder: 2,
    },
];

export function InformationShell({
    locale,
    seo,
    children,
}: {
    locale: Locale;
    seo: Seo;
    children: ReactNode;
}) {
    return (
        <div className="min-h-screen bg-brand-soft text-brand-charcoal dark:bg-[#11151a] dark:text-white">
            <Head title={seo.title}>
                <meta name="description" content={seo.description} />
            </Head>
            <TopBar links={topLinks} />
            <Navbar locale={locale} />
            <main id="content" className="pt-[4.75rem] lg:pt-[7rem]">
                {children}
            </main>
            <Footer locale={locale} quickLinks={quickLinks} links={{}} contact={{}} />
        </div>
    );
}

export function InformationHero({
    locale,
    title,
    description,
    eyebrow,
}: {
    locale: Locale;
    title?: string;
    description?: string;
    eyebrow?: string;
}) {
    const isEnglish = locale === 'en';
    return (
        <section className="border-b border-brand-silver bg-white dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-[1fr_.65fr] lg:py-16">
                <div>
                    <p className="text-sm font-bold uppercase tracking-[.2em] text-brand-purple">
                        {eyebrow ?? (isEnglish ? 'Information' : 'Informasi')}
                    </p>
                    <h1 className="mt-3 max-w-3xl text-4xl font-black leading-tight sm:text-5xl">
                        {title ??
                            (isEnglish ? 'Study Program Information' : 'Informasi Program Studi')}
                    </h1>
                    <p className="mt-5 max-w-2xl text-base leading-8 text-brand-graphite dark:text-slate-300">
                        {description ??
                            (isEnglish
                                ? 'Explore news, announcements, events, activity documentation, and official documents of the Informatics Study Program.'
                                : 'Temukan berita, pengumuman, agenda, dokumentasi kegiatan, dan dokumen resmi Program Studi Informatika.')}
                    </p>
                </div>
                <SearchPanel locale={locale} />
            </div>
        </section>
    );
}

export function SearchPanel({ locale }: { locale: Locale }) {
    const [query, setQuery] = useState('');
    const isEnglish = locale === 'en';
    const submit = (event: FormEvent) => {
        event.preventDefault();
        router.get(
            locale === 'en' ? '/en/search' : '/id/pencarian',
            { q: query },
            { preserveState: true },
        );
    };
    return (
        <form
            onSubmit={submit}
            className="self-end rounded-md border border-brand-silver bg-brand-soft p-4 dark:border-slate-700 dark:bg-[#11151a]"
        >
            <label htmlFor="information-search" className="text-sm font-bold">
                {isEnglish ? 'Search information' : 'Cari informasi'}
            </label>
            <div className="mt-3 flex gap-2">
                <input
                    id="information-search"
                    value={query}
                    onChange={(event) => setQuery(event.target.value)}
                    className="min-w-0 flex-1 rounded-md border border-brand-silver px-3 py-2 text-sm text-brand-charcoal"
                    placeholder={
                        isEnglish
                            ? 'Search news, documents, courses...'
                            : 'Cari berita, dokumen, mata kuliah...'
                    }
                />
                <button
                    className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-brand-deep text-white"
                    aria-label={isEnglish ? 'Search' : 'Cari'}
                >
                    <Search size={18} />
                </button>
            </div>
        </form>
    );
}

export function SectionBand({ title, children }: { title: string; children: ReactNode }) {
    return (
        <section className="mx-auto max-w-7xl px-4 py-10 sm:px-6">
            <h2 className="text-2xl font-black">{title}</h2>
            <div className="mt-5">{children}</div>
        </section>
    );
}

export function NewsCard({ item, locale }: { item: NewsItem; locale: Locale }) {
    return (
        <article className="rounded-md border border-brand-silver bg-white p-5 transition hover:-translate-y-0.5 hover:shadow-[var(--shadow-md)] dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="flex flex-wrap items-center gap-2 text-xs font-bold uppercase text-brand-purple">
                {item.category && <span>{item.category}</span>}
                <span>{formatDate(item.publishedAt, locale)}</span>
                <span>{item.readingTime} min</span>
            </div>
            <h3 className="mt-3 text-xl font-black leading-snug">
                <Link href={item.url}>{item.title}</Link>
            </h3>
            {item.excerpt && (
                <p className="mt-3 line-clamp-3 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                    {item.excerpt}
                </p>
            )}
            <Link
                href={item.url}
                className="mt-4 inline-flex items-center gap-2 text-sm font-bold text-brand-deep dark:text-brand-lavender"
            >
                {locale === 'en' ? 'Read article' : 'Baca berita'} <ArrowRight size={16} />
            </Link>
        </article>
    );
}

export function NewsGrid({ items, locale }: { items: NewsItem[]; locale: Locale }) {
    if (items.length === 0) {
        return (
            <EmptyState
                title={locale === 'en' ? 'No news found' : 'Belum ada berita'}
                description={
                    locale === 'en'
                        ? 'Published news will appear here.'
                        : 'Berita published akan tampil di sini.'
                }
            />
        );
    }
    return (
        <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            {items.map((item) => (
                <NewsCard key={item.id} item={item} locale={locale} />
            ))}
        </div>
    );
}

export function AnnouncementPriorityBadge({
    priority,
}: {
    priority: AnnouncementItem['priority'];
}) {
    const Icon =
        priority === 'urgent' ? AlertTriangle : priority === 'important' ? CheckCircle2 : FileText;
    return (
        <span className="inline-flex items-center gap-1 rounded-md border border-brand-silver px-2 py-1 text-xs font-black uppercase dark:border-slate-700">
            <Icon size={14} /> {priority}
        </span>
    );
}

export function AnnouncementCard({ item, locale }: { item: AnnouncementItem; locale: Locale }) {
    return (
        <article className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="flex flex-wrap items-center gap-2">
                <AnnouncementPriorityBadge priority={item.priority} />
                {item.expired && (
                    <span className="rounded-md bg-gray-100 px-2 py-1 text-xs font-bold text-gray-700">
                        {locale === 'en' ? 'Expired' : 'Berakhir'}
                    </span>
                )}
            </div>
            <h3 className="mt-3 text-lg font-black">
                <Link href={item.url}>{item.title}</Link>
            </h3>
            {item.excerpt && (
                <p className="mt-2 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                    {item.excerpt}
                </p>
            )}
            <div className="mt-3 text-xs text-brand-graphite dark:text-slate-300">
                {formatDate(item.publishedAt ?? item.startsAt, locale)}
                {item.expiresAt ? ` - ${formatDate(item.expiresAt, locale)}` : ''}
            </div>
        </article>
    );
}

export function EventStatusBadge({ status }: { status: EventItem['status'] }) {
    return (
        <span className="rounded-md border border-brand-silver px-2 py-1 text-xs font-black uppercase dark:border-slate-700">
            {status}
        </span>
    );
}

export function EventCard({ item, locale }: { item: EventItem; locale: Locale }) {
    const date = item.startAt ? new Date(item.startAt) : null;
    return (
        <article className="grid gap-4 rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128] sm:grid-cols-[96px_1fr]">
            <div className="bg-brand-lavender/20 flex h-24 flex-col items-center justify-center rounded-md text-brand-deep dark:text-white">
                <span className="text-3xl font-black">{date ? date.getDate() : '-'}</span>
                <span className="text-xs font-bold uppercase">
                    {date
                        ? date.toLocaleString(locale === 'en' ? 'en-US' : 'id-ID', {
                              month: 'short',
                          })
                        : ''}
                </span>
            </div>
            <div>
                <EventStatusBadge status={item.status} />
                <h3 className="mt-3 text-lg font-black">
                    <Link href={item.url}>{item.title}</Link>
                </h3>
                <p className="mt-2 text-sm text-brand-graphite dark:text-slate-300">
                    {formatDate(item.startAt, locale)} - {formatDate(item.endAt, locale)} |{' '}
                    {item.location}
                </p>
                <div className="mt-4 flex flex-wrap gap-2">
                    <a
                        href={item.icsUrl}
                        className="inline-flex items-center gap-2 rounded-md border border-brand-silver px-3 py-2 text-sm font-bold dark:border-slate-700"
                    >
                        <Download size={16} /> ICS
                    </a>
                    <a
                        href={item.googleCalendarUrl}
                        target="_blank"
                        rel="noreferrer"
                        className="inline-flex items-center gap-2 rounded-md bg-brand-deep px-3 py-2 text-sm font-bold text-white"
                    >
                        <CalendarDays size={16} /> Google
                    </a>
                </div>
            </div>
        </article>
    );
}

export function AlbumCard({ item }: { item: AlbumItem }) {
    return (
        <article className="overflow-hidden rounded-md border border-brand-silver bg-white dark:border-slate-700 dark:bg-[#1c2128]">
            <img src={item.cover} alt="" className="h-44 w-full object-cover" loading="lazy" />
            <div className="p-5">
                <div className="text-xs font-bold uppercase text-brand-purple">
                    {item.category ?? 'Album'} | {item.mediaCount} media
                </div>
                <h3 className="mt-2 text-lg font-black">
                    <Link href={item.url}>{item.title}</Link>
                </h3>
                {item.description && (
                    <p className="mt-2 line-clamp-2 text-sm text-brand-graphite dark:text-slate-300">
                        {item.description}
                    </p>
                )}
            </div>
        </article>
    );
}

export function MediaLightbox({ media }: { media: MediaItem[] }) {
    const [index, setIndex] = useState<number | null>(null);
    const active = index === null ? null : media[index];
    useEffect(() => {
        const onKey = (event: globalThis.KeyboardEvent) => {
            if (index === null) return;
            if (event.key === 'Escape') setIndex(null);
            if (event.key === 'ArrowRight') setIndex((index + 1) % media.length);
            if (event.key === 'ArrowLeft') setIndex((index - 1 + media.length) % media.length);
        };
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, [index, media.length]);

    return (
        <>
            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                {media.map((item, itemIndex) => (
                    <button
                        key={`${item.id}-${itemIndex}`}
                        onClick={() => setIndex(itemIndex)}
                        className="group overflow-hidden rounded-md border border-brand-silver bg-white text-left dark:border-slate-700 dark:bg-[#1c2128]"
                    >
                        <img
                            src={item.url}
                            alt={item.alt ?? item.caption ?? ''}
                            className="h-56 w-full object-cover transition group-hover:scale-[1.02]"
                            loading="lazy"
                        />
                        {item.caption && <span className="block p-3 text-sm">{item.caption}</span>}
                    </button>
                ))}
            </div>
            {active && (
                <div
                    className="fixed inset-0 z-[70] bg-black/85 p-4 text-white"
                    role="dialog"
                    aria-modal="true"
                >
                    <button
                        onClick={() => setIndex(null)}
                        className="absolute right-4 top-4 rounded-md bg-white/10 p-2"
                        aria-label="Close lightbox"
                    >
                        <X size={24} />
                    </button>
                    <div className="flex h-full items-center justify-center">
                        {active.provider ? (
                            <VideoEmbed item={active} />
                        ) : (
                            <img
                                src={active.url}
                                alt={active.alt ?? active.caption ?? ''}
                                className="max-h-[82vh] max-w-full object-contain"
                            />
                        )}
                    </div>
                    <div className="absolute bottom-4 left-4 right-4 text-center text-sm">
                        {index! + 1}/{media.length} {active.caption}
                    </div>
                </div>
            )}
        </>
    );
}

export function VideoEmbed({ item }: { item: MediaItem }) {
    const src = safeEmbedUrl(item.url);
    if (!src) return null;
    return (
        <iframe
            src={src}
            title={item.caption ?? 'Video'}
            className="aspect-video w-full max-w-4xl"
            loading="lazy"
            sandbox="allow-scripts allow-same-origin allow-presentation"
            allow="fullscreen; picture-in-picture"
        />
    );
}

export function DocumentCard({ item, locale }: { item: DocumentItem; locale: Locale }) {
    return (
        <article className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="flex items-start gap-3">
                <FileText className="mt-1 text-brand-purple" />
                <div className="min-w-0 flex-1">
                    <h3 className="font-black">{item.title}</h3>
                    {item.description && (
                        <p className="mt-1 text-sm text-brand-graphite dark:text-slate-300">
                            {item.description}
                        </p>
                    )}
                    <div className="mt-3 flex flex-wrap gap-2 text-xs font-bold uppercase text-brand-graphite dark:text-slate-300">
                        <span>{item.category}</span>
                        <span>{item.fileType}</span>
                        <span>{item.status}</span>
                        {item.year && <span>{item.year}</span>}
                    </div>
                </div>
                <a
                    href={item.downloadUrl}
                    className="inline-flex items-center gap-2 rounded-md bg-brand-deep px-3 py-2 text-sm font-bold text-white"
                    aria-label={`${locale === 'en' ? 'Download' : 'Unduh'} ${item.title}`}
                >
                    <Download size={16} />
                </a>
            </div>
        </article>
    );
}

export function FaqAccordion({ items }: { items: FaqItem[] }) {
    const [open, setOpen] = useState<number | null>(items[0]?.id ?? null);
    return (
        <div className="grid gap-3">
            {items.map((item) => (
                <section
                    key={item.id}
                    className="rounded-md border border-brand-silver bg-white dark:border-slate-700 dark:bg-[#1c2128]"
                >
                    <button
                        className="flex w-full items-center justify-between gap-4 p-4 text-left font-black"
                        onClick={() => setOpen(open === item.id ? null : item.id)}
                        aria-expanded={open === item.id}
                    >
                        {item.question}
                        <span>{open === item.id ? '-' : '+'}</span>
                    </button>
                    {open === item.id && (
                        <p className="border-t border-brand-silver p-4 text-sm leading-7 text-brand-graphite dark:border-slate-700 dark:text-slate-300">
                            {item.answer}
                        </p>
                    )}
                </section>
            ))}
        </div>
    );
}

export function FilterBar({
    locale,
    filters,
    categories,
}: {
    locale: Locale;
    filters: Record<string, string>;
    categories?: Array<{ slug: string; name: string }>;
}) {
    const [q, setQ] = useState(filters.q ?? '');
    const [category, setCategory] = useState(filters.category ?? '');
    const submit = (event: FormEvent) => {
        event.preventDefault();
        router.get(
            window.location.pathname,
            { ...filters, q, category: category || undefined },
            { preserveState: true, replace: true },
        );
    };
    return (
        <form
            onSubmit={submit}
            className="mb-6 grid gap-3 rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128] md:grid-cols-[1fr_220px_auto]"
        >
            <input
                value={q}
                onChange={(event) => setQ(event.target.value)}
                className="rounded-md border border-brand-silver px-3 py-2 text-sm text-brand-charcoal"
                placeholder={locale === 'en' ? 'Search' : 'Cari'}
            />
            {categories && (
                <select
                    value={category}
                    onChange={(event) => setCategory(event.target.value)}
                    className="rounded-md border border-brand-silver px-3 py-2 text-sm text-brand-charcoal"
                >
                    <option value="">
                        {locale === 'en' ? 'All categories' : 'Semua kategori'}
                    </option>
                    {categories.map((item) => (
                        <option key={item.slug} value={item.slug}>
                            {item.name}
                        </option>
                    ))}
                </select>
            )}
            <button className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white">
                {locale === 'en' ? 'Apply' : 'Terapkan'}
            </button>
        </form>
    );
}

export function Pagination<T>({ data }: { data: Paginated<T> }) {
    if (!data.links || data.links.length <= 3) return null;
    return (
        <nav className="mt-8 flex flex-wrap gap-2" aria-label="Pagination">
            {data.links.map((link, index) => (
                <Link
                    key={`${link.label}-${index}`}
                    href={link.url ?? '#'}
                    preserveScroll
                    className={`rounded-md border px-3 py-2 text-sm ${link.active ? 'bg-brand-lavender/20 border-brand-purple font-bold' : 'border-brand-silver dark:border-slate-700'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`}
                    dangerouslySetInnerHTML={{ __html: link.label }}
                />
            ))}
        </nav>
    );
}

export function ArticleContent({ html }: { html?: string | null }) {
    return (
        <div
            className="prose prose-slate dark:prose-invert max-w-[820px]"
            dangerouslySetInnerHTML={{ __html: html ?? '' }}
        />
    );
}

export function SearchResultGroup({ title, items }: { title: string; items: SearchResult[] }) {
    return (
        <section className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <h2 className="font-black">{title}</h2>
            <div className="mt-3 grid gap-3">
                {items.map((item) => (
                    <a
                        key={`${item.type}-${item.url}`}
                        href={item.url}
                        className="rounded-md border border-brand-silver p-3 dark:border-slate-700"
                    >
                        <div className="text-xs font-bold uppercase text-brand-purple">
                            {item.type}
                        </div>
                        <div className="mt-1 font-black">{item.title}</div>
                        {item.excerpt && (
                            <p className="mt-1 text-sm text-brand-graphite dark:text-slate-300">
                                {item.excerpt}
                            </p>
                        )}
                    </a>
                ))}
            </div>
        </section>
    );
}

export function ListingPage<T>({
    items,
    render,
    empty,
}: {
    items: Paginated<T>;
    render: (item: T) => ReactNode;
    empty: ReactNode;
}) {
    return items.data.length === 0 ? (
        <>{empty}</>
    ) : (
        <div className="grid gap-4">{items.data.map((item) => render(item))}</div>
    );
}

function formatDate(value: string | null | undefined, locale: Locale) {
    if (!value) return '-';
    return new Date(value).toLocaleDateString(locale === 'en' ? 'en-US' : 'id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
}

function safeEmbedUrl(url: string) {
    try {
        const parsed = new URL(url);
        if (parsed.hostname.includes('youtube.com'))
            return url.replace('watch?v=', 'embed/').replace('youtube.com', 'youtube-nocookie.com');
        if (parsed.hostname.includes('youtu.be'))
            return `https://www.youtube-nocookie.com/embed/${parsed.pathname.slice(1)}`;
        if (parsed.hostname.includes('vimeo.com'))
            return `https://player.vimeo.com/video/${parsed.pathname.slice(1)}`;
    } catch {
        return null;
    }
    return null;
}

export function GalleryIconLabel({ label }: { label: string }) {
    return (
        <span className="inline-flex items-center gap-2 text-sm font-bold text-brand-graphite dark:text-slate-300">
            <ImageIcon size={16} /> {label}
        </span>
    );
}

export function ExternalSafeLink({
    href,
    children,
}: {
    href?: string | null;
    children: ReactNode;
}) {
    if (!href) return null;
    return (
        <a
            href={href}
            target="_blank"
            rel="noreferrer"
            className="inline-flex items-center gap-2 rounded-md border border-brand-silver px-3 py-2 text-sm font-bold dark:border-slate-700"
        >
            {children} <ExternalLink size={15} />
        </a>
    );
}
