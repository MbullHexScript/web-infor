import { Course } from '@/types/academics';
import { Locale } from '@/types/homepage';
import { LecturerProfile, ManagementPosition, Partner, ProfileContent } from '@/types/profile';
import { Head, Link, router } from '@inertiajs/react';
import { BadgeCheck, Building2, ExternalLink, Search } from 'lucide-react';
import { FormEvent, ReactNode } from 'react';
import { Navbar } from '@/Components/Public/Navbar';

export function ProfileShell({
    locale,
    seo,
    children,
}: {
    locale: Locale;
    seo: { title: string; description: string };
    children: ReactNode;
}) {
    return (
        <div className="public-site min-h-screen overflow-x-hidden bg-brand-soft text-brand-charcoal dark:bg-[#11151a] dark:text-white">
            <Head title={seo.title}>
                <meta name="description" content={seo.description} />
            </Head>
            <Navbar locale={locale} hasTopBar={false} />
            <main id="content" className="pt-[4.75rem]">
                {children}
            </main>
        </div>
    );
}

export function ProfileHero({
    locale,
    title,
    description,
}: {
    locale: Locale;
    title: string;
    description: string;
}) {
    return (
        <section className="public-page-hero border-b border-brand-silver bg-white dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="mx-auto max-w-7xl px-4 py-14 sm:px-6">
                <div className="inline-flex items-center gap-2 rounded-md border border-brand-silver px-3 py-1 text-xs font-bold uppercase text-brand-purple dark:border-slate-600">
                    <Building2 size={14} />
                    {locale === 'id' ? 'Profil' : 'Profile'}
                </div>
                <h1 className="mt-5 max-w-4xl text-4xl font-black leading-tight text-brand-deep dark:text-white md:text-5xl">
                    {title}
                </h1>
                <p className="mt-4 max-w-3xl text-base leading-8 text-brand-graphite dark:text-slate-300 md:text-lg">
                    {description}
                </p>
            </div>
        </section>
    );
}

export function AboutProgramSection({
    page,
    locale,
}: {
    page?: ProfileContent | null;
    locale: Locale;
}) {
    return (
        <section className="public-card rounded-md border border-brand-silver bg-white p-6 dark:border-slate-700 dark:bg-[#1c2128]">
            <h2 className="text-2xl font-black">
                {locale === 'en' ? page?.title_en : page?.title_id}
            </h2>
            <p className="mt-4 whitespace-pre-line leading-8 text-brand-graphite dark:text-slate-300">
                {locale === 'en' ? page?.content_en || page?.content_id : page?.content_id}
            </p>
            <div className="mt-5 rounded-md bg-brand-soft p-4 text-sm font-bold text-brand-deep dark:bg-[#11151a] dark:text-brand-yellow">
                AMANAH DIGITAL UNTUK KEMASLAHATAN DAN RESILIENSI SIBER BANTEN
            </div>
        </section>
    );
}

export function HistoryTimeline({ locale }: { locale: Locale }) {
    const items = [
        [
            '2023',
            locale === 'id'
                ? 'Izin operasional Program Studi Informatika melalui Keputusan Menteri Nomor 400/E/O/2023 tanggal 2 Mei 2023.'
                : 'Operational approval through Ministerial Decree No. 400/E/O/2023 dated May 2, 2023.',
        ],
        [
            '2026',
            locale === 'id'
                ? 'Kurikulum OBE 2026 disiapkan sebagai penguatan struktur akademik dan profil lulusan.'
                : 'The 2026 OBE curriculum strengthens the academic structure and graduate profiles.',
        ],
    ];
    return (
        <ol className="grid gap-4">
            {items.map(([year, text]) => (
                <li
                    key={year}
                    className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]"
                >
                    <div className="font-mono text-sm font-black text-brand-purple">{year}</div>
                    <p className="mt-2 leading-7 text-brand-graphite dark:text-slate-300">{text}</p>
                </li>
            ))}
        </ol>
    );
}

export function VisionMissionSection({
    page,
    locale,
}: {
    page?: ProfileContent | null;
    locale: Locale;
}) {
    return (
        <div className="grid gap-5">
            <VisionHighlight page={page} locale={locale} />
            <div className="grid gap-4 md:grid-cols-3">
                {[1, 2, 3].map((number) => (
                    <MissionCard key={number} number={number} locale={locale} />
                ))}
            </div>
            <ObjectivesGrid locale={locale} />
        </div>
    );
}

export function VisionHighlight({
    page,
    locale,
}: {
    page?: ProfileContent | null;
    locale: Locale;
}) {
    return (
        <section className="public-button-primary rounded-md bg-brand-deep p-6 text-white">
            <div className="text-sm font-bold uppercase text-brand-yellow">
                {locale === 'id' ? 'Visi' : 'Vision'}
            </div>
            <p className="mt-3 text-2xl font-black leading-snug">
                {locale === 'en' ? page?.content_en || page?.content_id : page?.content_id}
            </p>
        </section>
    );
}

export function MissionCard({ number, locale }: { number: number; locale: Locale }) {
    const texts =
        locale === 'id'
            ? [
                  'Mengembangkan keilmuan informatika secara berkelanjutan.',
                  'Menguatkan pembelajaran inovatif dan relevan.',
                  'Mengintegrasikan nilai-nilai keislaman dalam teknologi.',
              ]
            : [
                  'Develop informatics scholarship sustainably.',
                  'Strengthen innovative and relevant learning.',
                  'Integrate Islamic values into technology.',
              ];
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="font-mono text-brand-purple">0{number}</div>
            <p className="mt-2 font-bold">{texts[number - 1]}</p>
        </article>
    );
}

export function ObjectivesGrid({ locale }: { locale: Locale }) {
    const items =
        locale === 'id'
            ? [
                  'Kompetensi profesional',
                  'Integritas akademik',
                  'Inovasi digital',
                  'Kemaslahatan publik',
              ]
            : [
                  'Professional competence',
                  'Academic integrity',
                  'Digital innovation',
                  'Public benefit',
              ];
    return (
        <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            {items.map((item) => (
                <div
                    key={item}
                    className="rounded-md bg-white p-4 text-sm font-bold shadow-sm dark:bg-[#1c2128]"
                >
                    {item}
                </div>
            ))}
        </div>
    );
}

export function HeadMessageCard({
    page,
    locale,
}: {
    page?: ProfileContent | null;
    locale: Locale;
}) {
    return (
        <section className="public-card rounded-md border border-brand-silver bg-white p-6 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="text-sm font-bold text-brand-purple">Ahmad Tabrani, M.T.I</div>
            <h2 className="mt-1 text-2xl font-black">
                {locale === 'id'
                    ? 'Ketua Program Studi Informatika'
                    : 'Head of Informatics Study Program'}
            </h2>
            <p className="mt-5 whitespace-pre-line leading-8 text-brand-graphite dark:text-slate-300">
                {locale === 'en' ? page?.content_en || page?.content_id : page?.content_id}
            </p>
        </section>
    );
}

export function ManagementTree({
    positions,
    locale,
}: {
    positions: ManagementPosition[];
    locale: Locale;
}) {
    return (
        <div className="grid gap-4 md:grid-cols-3">
            {positions.map((position) => (
                <ManagementCard key={position.id} position={position} locale={locale} />
            ))}
        </div>
    );
}

export function ManagementCard({
    position,
    locale,
}: {
    position: ManagementPosition;
    locale: Locale;
}) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="text-sm font-bold text-brand-purple">
                {locale === 'en'
                    ? position.position_en || position.position_id
                    : position.position_id}
            </div>
            <h3 className="mt-2 text-xl font-black">
                {position.full_name || position.person_name || '-'}
            </h3>
            <p className="text-sm text-brand-graphite dark:text-slate-300">
                {position.academic_title}
            </p>
        </article>
    );
}

export function LecturerFilters({
    filters,
    locale,
}: {
    filters: Record<string, string>;
    locale: Locale;
}) {
    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        const data = new FormData(event.currentTarget);
        router.get(
            locale === 'id' ? '/id/profil/dosen' : '/en/profile/lecturers',
            Object.fromEntries(data.entries()),
            { preserveState: true },
        );
    }
    return (
        <form
            onSubmit={submit}
            className="public-card rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]"
        >
            <label className="grid gap-1 text-sm font-bold">
                <span>{locale === 'id' ? 'Cari dosen' : 'Search lecturers'}</span>
                <div className="flex gap-2">
                    <input
                        name="q"
                        defaultValue={filters.q ?? ''}
                        className="min-w-0 flex-1 rounded-md border-brand-silver dark:bg-[#11151a]"
                    />
                    <button className="public-button-primary inline-flex items-center gap-2 rounded-md bg-brand-deep px-4 text-sm font-bold text-white">
                        <Search size={16} />
                        Cari
                    </button>
                </div>
            </label>
        </form>
    );
}

export function LecturerCard({ lecturer, locale }: { lecturer: LecturerProfile; locale: Locale }) {
    return (
        <Link
            href={
                locale === 'id'
                    ? `/id/profil/dosen/${lecturer.slug}`
                    : `/en/profile/lecturers/${lecturer.slug}`
            }
            className="public-card rounded-md border border-brand-silver bg-white p-5 transition hover:border-brand-purple dark:border-slate-700 dark:bg-[#1c2128]"
        >
            <InitialAvatar lecturer={lecturer} />
            <h3 className="mt-4 text-lg font-black">{lecturer.full_name}</h3>
            <p className="text-sm text-brand-graphite dark:text-slate-300">
                {lecturer.academic_title}
            </p>
            <p className="mt-2 text-xs font-bold text-brand-purple">
                {locale === 'en'
                    ? lecturer.academic_position_en || lecturer.academic_position_id
                    : lecturer.academic_position_id}
            </p>
        </Link>
    );
}

export function LecturerProfileHeader({ lecturer }: { lecturer: LecturerProfile }) {
    return (
        <section className="public-card rounded-md border border-brand-silver bg-white p-6 dark:border-slate-700 dark:bg-[#1c2128]">
            <InitialAvatar lecturer={lecturer} />
            <h1 className="mt-4 text-3xl font-black">{lecturer.full_name}</h1>
            <p className="text-brand-graphite dark:text-slate-300">{lecturer.academic_title}</p>
        </section>
    );
}

export function LecturerBiography({
    lecturer,
    locale,
}: {
    lecturer: LecturerProfile;
    locale: Locale;
}) {
    return (
        <Section title={locale === 'id' ? 'Biografi' : 'Biography'}>
            <p className="leading-7 text-brand-graphite dark:text-slate-300">
                {locale === 'en'
                    ? lecturer.biography_en || lecturer.biography_id
                    : lecturer.biography_id}
            </p>
        </Section>
    );
}

export function LecturerExpertise({ lecturer }: { lecturer: LecturerProfile }) {
    return (
        <Section title="Expertise">
            <div className="flex flex-wrap gap-2">
                {(lecturer.expertise_id ?? 'Perlu verifikasi').split(';').map((tag) => (
                    <span
                        key={tag}
                        className="bg-brand-lavender/25 rounded-md px-2 py-1 text-xs font-bold text-brand-deep"
                    >
                        {tag.trim()}
                    </span>
                ))}
            </div>
        </Section>
    );
}

export function LecturerEducationTimeline() {
    return (
        <Section title="Pendidikan">
            <p className="text-sm text-brand-graphite dark:text-slate-300">
                Riwayat pendidikan belum diverifikasi untuk publik.
            </p>
        </Section>
    );
}

export function LecturerAcademicLinks({ lecturer }: { lecturer: LecturerProfile }) {
    const links = [
        ['Scholar', lecturer.google_scholar_url],
        ['SINTA', lecturer.sinta_url],
        ['ORCID', lecturer.orcid_url],
        ['Scopus', lecturer.scopus_url],
        ['Website', lecturer.personal_website_url],
    ].filter(([, url]) => url);
    if (!links.length) return null;
    return (
        <Section title="Academic Links">
            <div className="flex flex-wrap gap-2">
                {links.map(([label, url]) => (
                    <a
                        key={label}
                        href={String(url)}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="inline-flex items-center gap-1 rounded-md border px-3 py-2 text-sm font-bold"
                    >
                        {label}
                        <ExternalLink size={14} />
                    </a>
                ))}
            </div>
        </Section>
    );
}

export function LecturerCourses({ courses }: { courses: Course[] }) {
    return (
        <Section title="Mata Kuliah">
            <div className="grid gap-2">
                {courses.map((course) => (
                    <div
                        key={course.code}
                        className="rounded-md border border-brand-silver p-3 text-sm dark:border-slate-700"
                    >
                        {course.code} - {course.name_id}
                    </div>
                ))}
            </div>
        </Section>
    );
}

export function LecturerPublications() {
    return (
        <Section title="Publikasi">
            <p className="text-sm text-brand-graphite dark:text-slate-300">
                Publikasi terpilih belum tersedia untuk ditampilkan.
            </p>
        </Section>
    );
}

export function StaffGrid({ staff }: { staff: LecturerProfile[] }) {
    if (!staff.length) return <EmptyState title="Data tenaga kependidikan sedang diperbarui." />;
    return (
        <div className="grid gap-4 md:grid-cols-3">
            {staff.map((item) => (
                <LecturerCard key={item.id} lecturer={item} locale="id" />
            ))}
        </div>
    );
}

export function AccreditationCard({
    current,
    locale,
}: {
    current?: Record<string, string> | null;
    locale: Locale;
}) {
    if (!current)
        return (
            <EmptyState
                title={
                    locale === 'id'
                        ? 'Informasi akreditasi sedang diperbarui.'
                        : 'Accreditation information is being updated.'
                }
            />
        );
    return (
        <Section title={locale === 'id' ? 'Akreditasi' : 'Accreditation'}>
            <p>{current.level_or_grade}</p>
        </Section>
    );
}

export function AccreditationHistory() {
    return null;
}

export function PartnersGrid({ partners }: { partners: Partner[] }) {
    return (
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            {partners.map((partner) => (
                <PartnerCard key={partner.id} partner={partner} />
            ))}
        </div>
    );
}

export function PartnerCard({ partner }: { partner: Partner }) {
    return (
        <article className="public-card rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="text-xs font-bold text-brand-purple">{partner.partner_type}</div>
            <h3 className="mt-2 text-lg font-black">{partner.name}</h3>
            <p className="mt-2 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                {partner.scope_id}
            </p>
            <VerificationBadge status={partner.verification_status} />
        </article>
    );
}

export function VerificationBadge({ status }: { status?: string | null }) {
    return (
        <span className="mt-3 inline-flex items-center gap-1 rounded-md bg-brand-soft px-2 py-1 text-xs font-bold text-brand-graphite dark:bg-[#11151a] dark:text-slate-300">
            <BadgeCheck size={13} />
            {status ?? 'pending'}
        </span>
    );
}

export function ProfileCompletionPanel() {
    return <EmptyState title="Beberapa data profil masih perlu verifikasi admin." />;
}

export function EmptyState({ title }: { title: string }) {
    return (
        <div className="public-card rounded-md border border-dashed border-brand-silver bg-white p-8 text-center text-brand-graphite dark:border-slate-700 dark:bg-[#1c2128] dark:text-slate-300">
            {title}
        </div>
    );
}

function Section({ title, children }: { title: string; children: ReactNode }) {
    return (
        <section className="public-card rounded-md border border-brand-silver bg-white p-6 dark:border-slate-700 dark:bg-[#1c2128]">
            <h2 className="text-xl font-black">{title}</h2>
            <div className="mt-4">{children}</div>
        </section>
    );
}

function InitialAvatar({ lecturer }: { lecturer: LecturerProfile }) {
    const initials = lecturer.full_name
        .split(' ')
        .slice(0, 2)
        .map((word) => word[0])
        .join('');
    if (lecturer.photo_id)
        return (
            <img
                src={`/storage/${lecturer.photo_id}`}
                alt={lecturer.photo_alt ?? `Foto ${lecturer.full_name}`}
                loading="lazy"
                className="aspect-[4/5] w-full rounded-md object-cover"
            />
        );
    return (
        <div className="public-button-primary flex aspect-[4/5] w-full items-center justify-center rounded-md bg-brand-deep text-4xl font-black text-white">
            {initials}
        </div>
    );
}
