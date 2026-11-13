import { EmptyState } from '@/Components/Public/EmptyState';
import { Footer } from '@/Components/Public/Footer';
import { Navbar } from '@/Components/Public/Navbar';
import { TopBar } from '@/Components/Public/TopBar';
import {
    Achievement,
    AdmissionPath,
    Alumni,
    Competition,
    Facility,
    Faq,
    Locale,
    Scholarship,
    Seo,
} from '@/types/student-life';
import { Head, Link, router, useForm } from '@inertiajs/react';
import {
    ArrowRight,
    BriefcaseBusiness,
    ExternalLink,
    GraduationCap,
    MapPin,
    Send,
} from 'lucide-react';
import { FormEvent, ReactNode, useState } from 'react';

export function StudentLifeShell({
    locale,
    seo,
    children,
}: {
    locale: Locale;
    seo: Seo;
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

export function StudentLifeHero({
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
                    <p className="text-sm font-bold uppercase text-brand-purple">
                        {locale === 'en' ? 'Student Life' : 'Kemahasiswaan'}
                    </p>
                    <h1 className="mt-3 max-w-4xl text-4xl font-black leading-tight sm:text-5xl">
                        {title ?? (locale === 'en' ? 'Student Life' : 'Kemahasiswaan')}
                    </h1>
                    <p className="mt-5 max-w-2xl text-base leading-8 text-brand-graphite dark:text-slate-300">
                        {description ??
                            (locale === 'en'
                                ? 'A space for Informatics students to learn, create, lead, compete, and contribute to society.'
                                : 'Ruang tumbuh bagi mahasiswa Informatika untuk belajar, berkarya, berorganisasi, berkompetisi, dan berkontribusi bagi masyarakat.')}
                    </p>
                </div>
                <div className="grid gap-3 sm:grid-cols-2">
                    {['Campus Life', 'Achievements', 'Admissions', 'Contact'].map((label) => (
                        <div
                            key={label}
                            className="rounded-md border border-brand-silver bg-brand-soft p-4 text-sm font-black dark:border-slate-700 dark:bg-[#11151a]"
                        >
                            {label}
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}

export function SectionBand({ children }: { children: ReactNode }) {
    return (
        <section className="public-section mx-auto max-w-7xl px-4 py-10 sm:px-6">
            {children}
        </section>
    );
}

export function FilterBar({
    filters,
    placeholder,
}: {
    filters: Record<string, string>;
    placeholder?: string;
}) {
    const [q, setQ] = useState(filters.q ?? '');

    function submit(event: FormEvent) {
        event.preventDefault();
        router.get(
            window.location.pathname,
            { ...filters, q: q || undefined },
            { preserveState: true, replace: true },
        );
    }

    return (
        <form
            onSubmit={submit}
            className="public-card grid gap-3 rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128] sm:grid-cols-[1fr_auto]"
        >
            <input
                value={q}
                onChange={(event) => setQ(event.target.value)}
                className="rounded-md border border-brand-silver px-3 py-2 text-sm text-brand-charcoal"
                placeholder={placeholder ?? 'Search'}
            />
            <button className="public-button-primary rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white">
                Apply
            </button>
        </form>
    );
}

export function Pagination({
    links,
}: {
    links: { url: string | null; label: string; active: boolean }[];
}) {
    if (!links || links.length <= 3) return null;

    return (
        <nav className="mt-8 flex flex-wrap gap-2" aria-label="Pagination">
            {links.map((link, index) => (
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

export function CampusLifeSection({
    locale,
    content,
}: {
    locale: Locale;
    content?: string | null;
}) {
    const items =
        locale === 'en'
            ? [
                  'Labs',
                  'Student organization',
                  'Seminars',
                  'Competitions',
                  'Student projects',
                  'Social and religious activities',
              ]
            : [
                  'Praktikum',
                  'Organisasi mahasiswa',
                  'Seminar',
                  'Kompetisi',
                  'Proyek mahasiswa',
                  'Kegiatan sosial dan keagamaan',
              ];

    return (
        <SectionBand>
            <h2 className="text-2xl font-black">
                {locale === 'en' ? 'Campus Life' : 'Kehidupan Mahasiswa'}
            </h2>
            <p className="mt-3 max-w-3xl text-sm leading-7 text-brand-graphite dark:text-slate-300">
                {content}
            </p>
            <div className="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                {items.map((item) => (
                    <div
                        key={item}
                        className="public-card rounded-md border border-brand-silver bg-white p-4 font-bold dark:border-slate-700 dark:bg-[#1c2128]"
                    >
                        {item}
                    </div>
                ))}
            </div>
        </SectionBand>
    );
}

export function AchievementLevelBadge({ level }: { level?: string | null }) {
    return (
        <span className="inline-flex rounded-md border border-brand-silver px-2 py-1 text-xs font-black uppercase dark:border-slate-700">
            {level ?? 'verified'}
        </span>
    );
}

export function ScholarshipStatusBadge({ status }: { status: string }) {
    return <StatusBadge status={status} />;
}

export function CompetitionStatusBadge({ status }: { status: string }) {
    return <StatusBadge status={status} />;
}

export function MessageStatusBadge({ status }: { status: string }) {
    return <StatusBadge status={status} />;
}

export function AchievementCard({ item, locale }: { item: Achievement; locale: Locale }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="flex items-center justify-between gap-3">
                <AchievementLevelBadge level={item.level} />
                <span className="text-sm font-bold">{item.year ?? '-'}</span>
            </div>
            <h3 className="mt-3 text-xl font-black">
                <Link href={item.url}>{item.title}</Link>
            </h3>
            <p className="mt-2 text-sm font-semibold">{item.recipient}</p>
            <p className="mt-1 text-sm text-brand-graphite dark:text-slate-300">{item.award}</p>
            <Link
                href={item.url}
                className="mt-4 inline-flex items-center gap-2 text-sm font-bold text-brand-deep dark:text-brand-lavender"
            >
                {locale === 'en' ? 'View detail' : 'Lihat detail'} <ArrowRight size={16} />
            </Link>
        </article>
    );
}

export function ScholarshipCard({ item, locale }: { item: Scholarship; locale: Locale }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <ScholarshipStatusBadge status={item.status} />
            <h3 className="mt-3 text-xl font-black">
                <Link href={item.url}>{item.name}</Link>
            </h3>
            <p className="mt-2 text-sm font-semibold">{item.provider}</p>
            <p className="mt-2 line-clamp-3 text-sm text-brand-graphite dark:text-slate-300">
                {item.description}
            </p>
            {item.endsAt && (
                <p className="mt-3 text-sm font-bold">
                    {locale === 'en' ? 'Deadline' : 'Batas akhir'}: {item.endsAt}
                </p>
            )}
        </article>
    );
}

export function CompetitionCard({ item, locale }: { item: Competition; locale: Locale }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <CompetitionStatusBadge status={item.status} />
            <h3 className="mt-3 text-xl font-black">
                <Link href={item.url}>{item.title}</Link>
            </h3>
            <p className="mt-2 text-sm font-semibold">{item.organizer}</p>
            <p className="mt-2 text-sm">{item.level}</p>
            <Link
                href={item.url}
                className="mt-4 inline-flex items-center gap-2 text-sm font-bold text-brand-deep dark:text-brand-lavender"
            >
                {locale === 'en' ? 'View competition' : 'Lihat kompetisi'} <ArrowRight size={16} />
            </Link>
        </article>
    );
}

export function AlumniCard({ item }: { item: Alumni }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <GraduationCap className="text-brand-purple" />
            <h3 className="mt-3 text-xl font-black">
                <Link href={item.url}>{item.name}</Link>
            </h3>
            <p className="mt-2 text-sm font-semibold">{item.position}</p>
            <p className="text-sm text-brand-graphite dark:text-slate-300">{item.company}</p>
            {item.quote && (
                <blockquote className="mt-3 border-l-4 border-brand-yellow pl-3 text-sm">
                    {item.quote}
                </blockquote>
            )}
        </article>
    );
}

export function FacilityCard({ item, locale }: { item: Facility; locale: Locale }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <MapPin className="text-brand-purple" />
            <h3 className="mt-3 text-xl font-black">
                <Link href={item.url}>{item.name}</Link>
            </h3>
            <p className="mt-2 text-sm font-semibold">{item.category}</p>
            <p className="mt-2 line-clamp-3 text-sm text-brand-graphite dark:text-slate-300">
                {item.description}
            </p>
            <p className="mt-3 text-sm">{item.location}</p>
            {item.capacity && (
                <p className="mt-1 text-sm font-bold">
                    {locale === 'en' ? 'Capacity' : 'Kapasitas'}: {item.capacity}
                </p>
            )}
        </article>
    );
}

export function AdmissionPathCard({ item, locale }: { item: AdmissionPath; locale: Locale }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <StatusBadge status={item.status} />
            <h3 className="mt-3 text-xl font-black">{item.name}</h3>
            <p className="mt-2 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                {item.description}
            </p>
            {item.registrationUrl && (
                <a
                    href={item.registrationUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="mt-4 inline-flex items-center gap-2 text-sm font-bold text-brand-deep dark:text-brand-lavender"
                >
                    {locale === 'en' ? 'Open portal' : 'Buka portal'} <ExternalLink size={16} />
                </a>
            )}
        </article>
    );
}

export function HmpsExternalLink({ url, locale }: { url: string; locale: Locale }) {
    return (
        <a
            href={url}
            target="_blank"
            rel="noopener noreferrer"
            className="public-button-primary inline-flex items-center gap-2 rounded-md bg-brand-deep px-4 py-3 text-sm font-bold text-white"
        >
            {locale === 'en' ? 'Open HMPS website' : 'Buka website HMPS'} <ExternalLink size={16} />
        </a>
    );
}

export function CareerRoadmap({ locale }: { locale: Locale }) {
    const careers = [
        'Software Engineer',
        'Web Developer',
        'Mobile Developer',
        'AI Engineer',
        'Data Analyst',
        'Cybersecurity Analyst',
        'Cloud Engineer',
        'Digital Innovator',
    ];
    return (
        <div
            className="grid gap-3 sm:grid-cols-2 lg:grid-cols-4"
            aria-label={locale === 'en' ? 'Career roadmap' : 'Peta prospek karier'}
        >
            {careers.map((career) => (
                <div
                    key={career}
                    className="public-card rounded-md border border-brand-silver bg-white p-4 text-sm font-bold dark:border-slate-700 dark:bg-[#1c2128]"
                >
                    <BriefcaseBusiness className="mb-2 text-brand-purple" size={18} />
                    {career}
                </div>
            ))}
        </div>
    );
}

export function AdmissionFaq({ items }: { items: Faq[] }) {
    if (items.length === 0) return <EmptyState title="FAQ" description="FAQ belum tersedia." />;
    return (
        <div className="grid gap-3">
            {items.map((item) => (
                <details
                    key={item.question}
                    className="public-card rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]"
                >
                    <summary className="cursor-pointer font-black">{item.question}</summary>
                    <p className="mt-3 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                        {item.answer}
                    </p>
                </details>
            ))}
        </div>
    );
}

export function ContactInfoCard({
    contact,
    locale,
}: {
    contact: Record<string, string | null>;
    locale: Locale;
}) {
    return (
        <div className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <h2 className="text-xl font-black">
                {locale === 'en' ? 'Contact Information' : 'Informasi Kontak'}
            </h2>
            <p className="mt-3 font-semibold">informatika@uinbanten.ac.id</p>
            <p className="mt-2 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                {locale === 'en' ? contact.address_en : contact.address_id}
            </p>
            <p className="mt-2 text-sm">
                {locale === 'en' ? contact.service_hours_en : contact.service_hours_id}
            </p>
        </div>
    );
}

export function ContactForm({ locale }: { locale: Locale }) {
    const form = useForm({
        name: '',
        email: '',
        phone: '',
        category: 'general',
        subject: '',
        message: '',
        consent: false,
        website: '',
    });

    function submit(event: FormEvent) {
        event.preventDefault();
        form.post(locale === 'en' ? '/en/contact' : '/id/kontak', { preserveScroll: true });
    }

    return (
        <form
            onSubmit={submit}
            className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]"
        >
            <h2 className="text-xl font-black">
                {locale === 'en' ? 'Send a Message' : 'Kirim Pesan'}
            </h2>
            <div className="mt-5 grid gap-4">
                <Input
                    label={locale === 'en' ? 'Name' : 'Nama'}
                    value={form.data.name}
                    error={form.errors.name}
                    onChange={(value) => form.setData('name', value)}
                />
                <Input
                    label="Email"
                    value={form.data.email}
                    error={form.errors.email}
                    onChange={(value) => form.setData('email', value)}
                />
                <Input
                    label={locale === 'en' ? 'Phone (optional)' : 'Telepon (opsional)'}
                    value={form.data.phone}
                    error={form.errors.phone}
                    onChange={(value) => form.setData('phone', value)}
                />
                <ContactCategorySelect
                    value={form.data.category}
                    onChange={(value) => form.setData('category', value)}
                />
                <Input
                    label={locale === 'en' ? 'Subject' : 'Subjek'}
                    value={form.data.subject}
                    error={form.errors.subject}
                    onChange={(value) => form.setData('subject', value)}
                />
                <label className="grid gap-2 text-sm font-bold">
                    {locale === 'en' ? 'Message' : 'Pesan'}
                    <textarea
                        className="min-h-36 rounded-md border-brand-silver dark:bg-[#11151a]"
                        value={form.data.message}
                        onChange={(event) => form.setData('message', event.target.value)}
                    />
                    {form.errors.message && (
                        <span className="text-xs text-red-600">{form.errors.message}</span>
                    )}
                </label>
                <input
                    type="text"
                    className="hidden"
                    tabIndex={-1}
                    autoComplete="off"
                    value={form.data.website}
                    onChange={(event) => form.setData('website', event.target.value)}
                />
                <label className="flex gap-3 text-sm">
                    <input
                        type="checkbox"
                        checked={form.data.consent}
                        onChange={(event) => form.setData('consent', event.target.checked)}
                    />
                    <span>
                        {locale === 'en'
                            ? 'I agree that my message is stored for follow-up.'
                            : 'Saya setuju pesan saya disimpan untuk tindak lanjut.'}
                    </span>
                </label>
                {form.errors.consent && (
                    <span className="text-xs text-red-600">{form.errors.consent}</span>
                )}
                {form.recentlySuccessful && <ContactSuccessState locale={locale} />}
                {Object.keys(form.errors).length > 0 && <ContactErrorState locale={locale} />}
                <button
                    disabled={form.processing}
                    className="public-button-primary inline-flex w-fit items-center gap-2 rounded-md bg-brand-deep px-4 py-3 text-sm font-bold text-white"
                >
                    <Send size={16} /> {locale === 'en' ? 'Submit' : 'Kirim'}
                </button>
            </div>
        </form>
    );
}

export function ContactCategorySelect({
    value,
    onChange,
}: {
    value: string;
    onChange: (value: string) => void;
}) {
    const options = [
        'academic',
        'admissions',
        'rps',
        'partnership',
        'research',
        'student_life',
        'general',
    ];
    return (
        <label className="grid gap-2 text-sm font-bold">
            Kategori
            <select
                className="rounded-md border-brand-silver dark:bg-[#11151a]"
                value={value}
                onChange={(event) => onChange(event.target.value)}
            >
                {options.map((option) => (
                    <option key={option} value={option}>
                        {option}
                    </option>
                ))}
            </select>
        </label>
    );
}

export function MapEmbed({
    contact,
    locale,
}: {
    contact: Record<string, string | null>;
    locale: Locale;
}) {
    if (!contact.map_embed_url) {
        return (
            <div className="public-card rounded-md border border-brand-silver bg-white p-5 text-sm dark:border-slate-700 dark:bg-[#1c2128]">
                {locale === 'en'
                    ? 'Map embed is pending verification.'
                    : 'Embed peta menunggu verifikasi.'}
            </div>
        );
    }
    return (
        <iframe
            title={locale === 'en' ? 'Campus map' : 'Peta kampus'}
            src={contact.map_embed_url}
            loading="lazy"
            className="h-80 w-full rounded-md border border-brand-silver"
        />
    );
}

export function SocialLinks() {
    return null;
}

export function ContactSuccessState({ locale }: { locale: Locale }) {
    return (
        <div
            role="status"
            className="rounded-md border border-green-300 bg-green-50 p-3 text-sm font-bold text-green-800"
        >
            {locale === 'en' ? 'Message received.' : 'Pesan berhasil diterima.'}
        </div>
    );
}

export function ContactErrorState({ locale }: { locale: Locale }) {
    return (
        <div
            role="alert"
            className="rounded-md border border-red-300 bg-red-50 p-3 text-sm font-bold text-red-800"
        >
            {locale === 'en' ? 'Please review the form fields.' : 'Periksa kembali isian formulir.'}
        </div>
    );
}

export function AdminInbox({ children }: { children: ReactNode }) {
    return (
        <div className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            {children}
        </div>
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

export function StatusBadge({ status }: { status?: string | null }) {
    return (
        <span className="inline-flex rounded-md border border-brand-silver px-2 py-1 text-xs font-black uppercase dark:border-slate-700">
            {status ?? 'pending'}
        </span>
    );
}

function Input({
    label,
    value,
    error,
    onChange,
}: {
    label: string;
    value: string;
    error?: string;
    onChange: (value: string) => void;
}) {
    return (
        <label className="grid gap-2 text-sm font-bold">
            {label}
            <input
                className="rounded-md border-brand-silver dark:bg-[#11151a]"
                value={value}
                onChange={(event) => onChange(event.target.value)}
            />
            {error && <span className="text-xs text-red-600">{error}</span>}
        </label>
    );
}

export const AchievementFilters = FilterBar;
export const ScholarshipFilters = FilterBar;
export const FacilityFilters = FilterBar;
export const AchievementsGrid = Grid;
export const ScholarshipsList = Grid;
export const CompetitionList = Grid;
export const AlumniGrid = Grid;
export const AchievementDetail = AchievementCard;
export const AlumniStory = AlumniCard;
export const FacilityDetail = FacilityCard;
export const AdmissionPaths = Grid;
export const AdmissionHero = StudentLifeHero;
export const WhyInformaticsSection = CampusLifeSection;
export const AdmissionAdvantages = CampusLifeSection;
export const MbkmProgramCard = FacilityCard;
