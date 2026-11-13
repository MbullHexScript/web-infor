import { EmptyState } from '@/Components/Public/EmptyState';
import { Footer } from '@/Components/Public/Footer';
import { Navbar } from '@/Components/Public/Navbar';
import { TopBar } from '@/Components/Public/TopBar';
import { FilterBar, Pagination, SectionBand } from '@/Components/Information/InformationComponents';
import { Locale } from '@/types/homepage';
import {
    CommunityService,
    ExpertiseGroup,
    IntellectualProperty,
    Journal,
    Product,
    Publication,
    ResearchProject,
} from '@/types/research';
import { Head, Link } from '@inertiajs/react';
import { ArrowRight, Download, ExternalLink, Layers3, ShieldCheck } from 'lucide-react';
import { ReactNode } from 'react';

export function ResearchShell({
    locale,
    seo,
    children,
}: {
    locale: Locale;
    seo: { title: string; description: string };
    children: ReactNode;
}) {
    return (
        <div className="public-site min-h-screen bg-brand-soft text-brand-charcoal dark:bg-[#11151a] dark:text-white">
            <Head title={seo.title}>
                <meta name="description" content={seo.description} />
            </Head>
            <TopBar
                links={[
                    { label: 'UIN SMH Banten', url: 'https://uinbanten.ac.id/', external: true },
                ]}
            />
            <Navbar locale={locale} />
            <main className="pt-[4.75rem] lg:pt-[7rem]">{children}</main>
            <Footer locale={locale} quickLinks={[]} links={{}} contact={{}} />
        </div>
    );
}

export function ResearchHero({
    locale,
    title,
    description,
}: {
    locale: Locale;
    title?: string;
    description?: string;
}) {
    return (
        <section className="public-page-hero border-b border-brand-silver bg-white dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-[1fr_.72fr]">
                <div>
                    <p className="text-sm font-bold uppercase tracking-[.2em] text-brand-purple">
                        {locale === 'en' ? 'Research' : 'Riset'}
                    </p>
                    <h1 className="mt-3 max-w-4xl text-4xl font-black leading-tight sm:text-5xl">
                        {title ??
                            (locale === 'en' ? 'Research and Innovation' : 'Riset dan Inovasi')}
                    </h1>
                    <p className="mt-5 max-w-2xl text-base leading-8 text-brand-graphite dark:text-slate-300">
                        {description ??
                            (locale === 'en'
                                ? 'Advancing research, technological innovation, scholarly publication, and community engagement to address the challenges of digital transformation.'
                                : 'Mengembangkan penelitian, inovasi teknologi, publikasi ilmiah, dan pengabdian kepada masyarakat untuk menjawab tantangan transformasi digital.')}
                    </p>
                </div>
                <div className="grid content-end gap-3">
                    {['Research', 'Publication', 'Innovation'].map((label) => (
                        <div
                            key={label}
                            className="rounded-md border border-brand-silver bg-brand-soft p-4 dark:border-slate-700 dark:bg-[#11151a]"
                        >
                            <span className="text-sm font-bold text-brand-purple">{label}</span>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}

export function ResearchCard({ item, locale }: { item: ResearchProject; locale: Locale }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <Meta year={item.year} status={item.status} />
            <h3 className="mt-3 text-xl font-black">
                <Link href={item.url ?? '#'}>{item.title}</Link>
            </h3>
            <p className="mt-2 line-clamp-3 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                {item.summary}
            </p>
            <div className="mt-3 text-sm font-semibold">{item.lead}</div>
            <Link
                href={item.url ?? '#'}
                className="mt-4 inline-flex items-center gap-2 text-sm font-bold text-brand-deep dark:text-brand-lavender"
            >
                {locale === 'en' ? 'View research' : 'Lihat penelitian'} <ArrowRight size={16} />
            </Link>
        </article>
    );
}

export function ServiceCard({ item, locale }: { item: CommunityService; locale: Locale }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <Meta year={item.year} status={item.status} />
            <h3 className="mt-3 text-xl font-black">
                <Link href={item.url ?? '#'}>{item.title}</Link>
            </h3>
            <p className="mt-2 text-sm text-brand-graphite dark:text-slate-300">{item.location}</p>
            <p className="mt-2 line-clamp-3 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                {item.summary}
            </p>
            <Link
                href={item.url ?? '#'}
                className="mt-4 inline-flex items-center gap-2 text-sm font-bold text-brand-deep dark:text-brand-lavender"
            >
                {locale === 'en' ? 'View activity' : 'Lihat kegiatan'} <ArrowRight size={16} />
            </Link>
        </article>
    );
}

export function PublicationItem({ item }: { item: Publication }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="text-xs font-bold uppercase text-brand-purple">
                {item.type} | {item.year}
            </div>
            <h3 className="mt-2 text-lg font-black">{item.title}</h3>
            <p className="mt-2 text-sm text-brand-graphite dark:text-slate-300">{item.authors}</p>
            <p className="mt-1 text-sm">{item.venue}</p>
            <div className="mt-4 flex flex-wrap gap-2">
                {item.doi && (
                    <span className="rounded-md border border-brand-silver px-2 py-1 text-xs font-bold dark:border-slate-700">
                        DOI {item.doi}
                    </span>
                )}
                {item.url && <ExternalButton href={item.url} label="URL" />}
                <ExternalButton
                    href={item.bibtexUrl}
                    label="BibTeX"
                    icon={<Download size={15} />}
                />
                <ExternalButton href={item.risUrl} label="RIS" icon={<Download size={15} />} />
            </div>
        </article>
    );
}

export function ProductCard({ item, locale }: { item: Product; locale: Locale }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <Meta year={item.year} status={item.status} />
            <h3 className="mt-3 text-xl font-black">
                <Link href={item.url ?? '#'}>{item.title}</Link>
            </h3>
            <p className="mt-2 line-clamp-3 text-sm text-brand-graphite dark:text-slate-300">
                {item.summary}
            </p>
            <div className="mt-3 flex flex-wrap gap-2">
                {item.technologies.map((tech) => (
                    <TechnologyBadge key={tech} label={tech} />
                ))}
            </div>
            <Link
                href={item.url ?? '#'}
                className="mt-4 inline-flex items-center gap-2 text-sm font-bold text-brand-deep dark:text-brand-lavender"
            >
                {locale === 'en' ? 'View product' : 'Lihat produk'} <ArrowRight size={16} />
            </Link>
        </article>
    );
}

export function TechnologyBadge({ label }: { label: string }) {
    return (
        <span className="bg-brand-lavender/20 rounded-md px-2 py-1 text-xs font-bold text-brand-deep dark:text-brand-lavender">
            {label}
        </span>
    );
}

export function IpCard({ item }: { item: IntellectualProperty }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="flex items-center gap-2 text-xs font-bold uppercase text-brand-purple">
                <ShieldCheck size={15} /> {item.type} {item.year}
            </div>
            <h3 className="mt-2 text-lg font-black">{item.title}</h3>
            <p className="mt-2 text-sm text-brand-graphite dark:text-slate-300">{item.inventors}</p>
            {item.registrationNumber && (
                <p className="mt-2 font-mono text-xs">{item.registrationNumber}</p>
            )}
        </article>
    );
}

export function ExpertiseGroupCard({ item }: { item: ExpertiseGroup }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <Layers3 className="text-brand-purple" />
            <h3 className="mt-3 text-xl font-black">
                <Link href={item.url}>{item.name}</Link>
            </h3>
            <p className="mt-2 text-sm text-brand-graphite dark:text-slate-300">{item.focus}</p>
        </article>
    );
}

export function JournalCard({ item, locale }: { item: Journal; locale: Locale }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="public-button-primary flex h-14 w-14 items-center justify-center rounded-md bg-brand-deep text-xl font-black text-white">
                {item.code?.[0]}
            </div>
            <JournalStatusBadge status={item.status} />
            <h3 className="mt-3 text-xl font-black">
                <Link href={item.url}>{item.name}</Link>
            </h3>
            <p className="mt-1 text-sm font-semibold">{item.fullName}</p>
            <p className="mt-3 text-sm text-brand-graphite dark:text-slate-300">{item.tagline}</p>
            <Link
                href={item.url}
                className="mt-4 inline-flex items-center gap-2 text-sm font-bold text-brand-deep dark:text-brand-lavender"
            >
                {locale === 'en' ? 'Open journal' : 'Buka jurnal'} <ArrowRight size={16} />
            </Link>
        </article>
    );
}

export function JournalStatusBadge({ status }: { status: string }) {
    return (
        <span className="mt-3 inline-flex rounded-md border border-brand-silver px-2 py-1 text-xs font-black uppercase dark:border-slate-700">
            {status}
        </span>
    );
}

export function ScopeList({ items }: { items: string[] }) {
    return (
        <ul className="grid gap-2 sm:grid-cols-2">
            {items.map((item) => (
                <li
                    key={item}
                    className="rounded-md border border-brand-silver p-3 text-sm dark:border-slate-700"
                >
                    {item}
                </li>
            ))}
        </ul>
    );
}

export function Grid<T>({
    items,
    render,
    empty,
}: {
    items: T[];
    render: (item: T) => ReactNode;
    empty: string;
}) {
    if (items.length === 0) return <EmptyState title="Empty" description={empty} />;
    return <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">{items.map(render)}</div>;
}

export { FilterBar, Pagination, SectionBand };

function Meta({ year, status }: { year?: number | null; status?: string | null }) {
    return (
        <div className="text-xs font-bold uppercase text-brand-purple">
            {year ?? '-'} {status ? `| ${status}` : ''}
        </div>
    );
}

function ExternalButton({ href, label, icon }: { href: string; label: string; icon?: ReactNode }) {
    return (
        <a
            href={href}
            className="inline-flex items-center gap-2 rounded-md border border-brand-silver px-3 py-2 text-xs font-bold dark:border-slate-700"
            target={href.startsWith('http') ? '_blank' : undefined}
            rel={href.startsWith('http') ? 'noreferrer' : undefined}
        >
            {icon ?? <ExternalLink size={15} />} {label}
        </a>
    );
}
