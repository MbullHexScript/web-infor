import { Reveal } from '@/Components/Public/Reveal';
import { Locale } from '@/types/homepage';
import { Link } from '@inertiajs/react';
import {
    ArrowRight,
    Award,
    BookOpen,
    BrainCircuit,
    Database,
    ShieldCheck,
    Target,
    Users,
} from 'lucide-react';

export function HomeHero({ hero, locale }: { hero: Record<string, string>; locale: Locale }) {
    const isEnglish = locale === 'en';
    const overviewCards = [
        {
            title: isEnglish ? 'Vision' : 'Visi',
            body: isEnglish
                ? 'Excellent informatics program with Islamic character.'
                : 'Unggul, adaptif, dan berkarakter Islami.',
            icon: Target,
        },
        {
            title: isEnglish ? 'Mission' : 'Misi',
            body: isEnglish
                ? 'Education, research, and service for public benefit.'
                : 'Pendidikan, riset, dan pengabdian berbasis teknologi.',
            icon: Users,
        },
        {
            title: isEnglish ? 'Accreditation' : 'Akreditasi',
            body: isEnglish
                ? 'Committed to continuous quality improvement.'
                : 'Berkomitmen pada mutu dan perbaikan berkelanjutan.',
            icon: Award,
        },
        {
            title: isEnglish ? 'OBE Curriculum' : 'Kurikulum OBE',
            body: isEnglish
                ? 'Outcome-Based Education for career-ready graduates.'
                : 'Berbasis capaian lulusan dan kebutuhan dunia kerja.',
            icon: BookOpen,
        },
    ];

    return (
        <section className="public-home-hero relative isolate overflow-hidden bg-brand-soft text-brand-charcoal">
            <div className="public-inverse public-hero-stage relative overflow-hidden bg-[#362743]">
                <div className="absolute inset-0 bg-[linear-gradient(110deg,#211b2c_0%,#362743_65%,#49365b_100%)]" />
                <div className="absolute inset-0 bg-[radial-gradient(circle_at_72%_36%,rgba(124,43,216,.34),transparent_36%),radial-gradient(circle_at_20%_72%,rgba(251,195,31,.16),transparent_30%)] opacity-20" />
                <div className="absolute inset-y-0 right-0 hidden w-[56%] overflow-hidden lg:block">
                    <div className="absolute inset-0 bg-[radial-gradient(circle_at_54%_46%,rgba(251,195,31,.18),transparent_24%),radial-gradient(circle_at_62%_42%,rgba(124,43,216,.42),transparent_38%),linear-gradient(90deg,rgba(18,10,35,.78)_0%,rgba(18,10,35,.42)_42%,rgba(18,10,35,.04)_100%)] opacity-20" />
                    <div className="absolute inset-0 opacity-[.04] [background-image:linear-gradient(135deg,rgba(255,255,255,.22)_1px,transparent_1px)] [background-size:24px_24px]" />
                </div>
                <div className="pointer-events-none absolute inset-0 opacity-[.04] [background-image:linear-gradient(90deg,rgba(255,255,255,.30)_1px,transparent_1px),linear-gradient(rgba(255,255,255,.30)_1px,transparent_1px)] [background-size:54px_54px]" />
                <div className="pointer-events-none absolute right-[18%] top-[24%] hidden h-64 w-64 rounded-full border border-white/10 lg:block" />
                <div className="pointer-events-none absolute right-[24%] top-[38%] hidden h-40 w-40 rounded-full border border-white/10 lg:block" />

                <div className="relative z-10 mx-auto grid max-w-7xl items-center gap-12 px-4 pb-28 pt-32 sm:px-6 sm:pb-32 sm:pt-36 lg:min-h-[720px] lg:grid-cols-[1.1fr_.9fr] lg:gap-12 lg:pb-36 lg:pt-48">
                    <div className="max-w-[40rem]">
                        <Reveal>
                            <p className="text-sm font-semibold tracking-wide text-brand-lavender sm:text-base">
                                {isEnglish ? 'Welcome to the Website' : 'Selamat Datang di Website'}
                            </p>
                        </Reveal>
                        <Reveal delay={100}>
                            <h1 className="public-hero-title relative mt-5 max-w-[40rem] overflow-hidden font-heading text-[2.5rem] font-semibold leading-[1.08] tracking-tight text-white sm:text-5xl lg:text-[3.5rem] xl:text-[4rem]">
                                <span className="block">Program Studi Informatika</span>
                                <span className="mt-4 block text-[.43em] font-medium leading-snug tracking-normal text-white/90">
                                    UIN Sultan Maulana Hasanuddin Banten
                                </span>
                                <span
                                    aria-hidden="true"
                                    className="pointer-events-none absolute inset-y-0 -left-1/4 w-1/4 skew-x-[-18deg] bg-gradient-to-r from-transparent via-white/80 to-transparent opacity-20 mix-blend-screen motion-safe:animate-[hero-title-sheen_5.2s_ease-in-out_infinite]"
                                />
                            </h1>
                        </Reveal>
                        <Reveal delay={200}>
                            <p className="mt-5 max-w-[40rem] text-base font-medium leading-7 text-brand-lavender sm:text-xl sm:leading-8 lg:text-lg">
                                {hero.tagline}
                            </p>
                        </Reveal>
                        <Reveal delay={300}>
                            <p className="mt-4 max-w-[40rem] text-base font-normal leading-7 text-white/85 sm:text-lg sm:leading-8">
                                {isEnglish ? hero.description_en : hero.description_id}
                            </p>
                        </Reveal>
                        <Reveal delay={400}>
                            <div className="mt-8 flex flex-wrap gap-4">
                                <Link
                                    href={isEnglish ? '/en/profile/about' : '/id/profil/tentang'}
                                    className="public-button-primary group inline-flex min-h-12 items-center gap-3 rounded-full px-6 py-3 text-sm font-semibold transition hover:-translate-y-0.5"
                                >
                                    {isEnglish ? 'Study Program Profile' : 'Profil Prodi'}
                                    <ArrowRight
                                        className="transition group-hover:translate-x-1"
                                        size={18}
                                    />
                                </Link>
                                <Link
                                    href={
                                        isEnglish
                                            ? '/en/academics/curriculum'
                                            : '/id/akademik/kurikulum'
                                    }
                                    className="public-button-secondary inline-flex min-h-12 items-center gap-3 rounded-full border border-white/70 bg-white/5 px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-white/10"
                                >
                                    <BookOpen size={18} />
                                    {isEnglish ? 'OBE Curriculum' : 'Kurikulum OBE'}
                                </Link>
                            </div>
                        </Reveal>
                    </div>

                    <Reveal delay={180}>
                        <div className="public-hero-art relative min-h-[24rem] overflow-hidden rounded-[1.2rem] border border-white/20 bg-white/5 shadow-none backdrop-blur-sm lg:min-h-[25rem]">
                            <div className="absolute inset-0 bg-[radial-gradient(circle_at_50%_48%,rgba(251,195,31,.18),transparent_23%),radial-gradient(circle_at_56%_44%,rgba(124,43,216,.42),transparent_40%)] opacity-20" />
                            <div className="absolute inset-0 opacity-[.04] [background-image:linear-gradient(135deg,rgba(255,255,255,.28)_1px,transparent_1px)] [background-size:24px_24px]" />
                            <div className="border-white/28 absolute left-1/2 top-1/2 h-[22rem] w-[22rem] -translate-x-1/2 -translate-y-1/2 rounded-full border motion-safe:animate-[orbit-spin_52s_linear_infinite]" />
                            <div className="border-brand-yellow/35 absolute left-1/2 top-1/2 h-[14rem] w-[14rem] -translate-x-1/2 -translate-y-1/2 rounded-full border motion-safe:animate-[orbit-spin_38s_linear_infinite_reverse]" />
                            <div className="absolute left-[58%] top-[22%] h-20 w-20 rounded-full border border-white/10" />
                            <div className="absolute right-6 top-6 flex gap-3">
                                <img
                                    src="/assets/brand/logo-uin-smh-banten-512.webp"
                                    alt="Logo UIN SMH Banten"
                                    className="h-16 w-16 rounded-[var(--radius-sm)] border border-white/75 bg-white object-contain p-2 shadow-sm backdrop-blur"
                                    width="64"
                                    height="64"
                                />
                                <img
                                    src="/assets/brand/logo-informatika-official-256.png"
                                    alt="Logo Program Studi Informatika"
                                    className="h-16 w-16 rounded-full border border-white/75 bg-white object-contain p-1.5 shadow-sm backdrop-blur"
                                    width="64"
                                    height="64"
                                />
                            </div>
                            <div className="absolute left-1/2 top-1/2 z-10 flex h-44 w-44 -translate-x-[64%] -translate-y-1/2 items-center justify-center rounded-full border border-white/35 bg-white p-3 shadow-md backdrop-blur-xl sm:h-52 sm:w-52">
                                <img
                                    src="/assets/brand/logo-informatika-official-256.png"
                                    alt="Logo Program Studi Informatika"
                                    className="h-full w-full rounded-full object-contain motion-safe:animate-[hero-logo-float_5s_ease-in-out_infinite]"
                                    width="208"
                                    height="208"
                                />
                            </div>
                            <div className="absolute left-1/2 top-1/2 z-10 flex h-32 w-32 -translate-y-1/2 translate-x-[34%] items-center justify-center rounded-[1rem] border border-white/35 bg-white p-4 shadow-md backdrop-blur-xl sm:h-40 sm:w-40">
                                <img
                                    src="/assets/brand/logo-uin-smh-banten-512.webp"
                                    alt="Logo UIN Sultan Maulana Hasanuddin Banten"
                                    className="h-full w-full object-contain motion-safe:animate-[hero-logo-float_5.6s_ease-in-out_infinite]"
                                    width="160"
                                    height="160"
                                />
                            </div>
                            <div className="absolute bottom-7 left-6 right-6 z-20 grid grid-cols-3 gap-2">
                                {[
                                    { label: 'AI', icon: BrainCircuit },
                                    { label: 'Data', icon: Database },
                                    { label: 'Cyber', icon: ShieldCheck },
                                ].map((item) => {
                                    const Icon = item.icon;
                                    return (
                                        <div
                                            key={item.label}
                                            className="flex min-h-11 items-center justify-center gap-2 rounded-[var(--radius-sm)] border border-white/35 bg-white/10 px-3 text-xs font-semibold text-white backdrop-blur"
                                        >
                                            <Icon size={16} className="text-brand-yellow" />
                                            {item.label}
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </Reveal>
                </div>
            </div>

            <div className="relative z-20 mx-auto -mt-16 max-w-7xl px-4 sm:-mt-20 sm:px-6 lg:-mt-20">
                <div className="public-card public-overview grid overflow-hidden rounded-[1.5rem] border border-brand-silver bg-white md:grid-cols-2 xl:grid-cols-4">
                    {overviewCards.map((card, index) => {
                        const Icon = card.icon;
                        return (
                            <div
                                key={card.title}
                                className="hover:bg-brand-lavender/10 group relative min-h-36 border-brand-silver p-6 transition md:border-r md:last:border-r-0"
                            >
                                <div className="absolute inset-x-6 top-0 h-1 origin-left scale-x-0 bg-gradient-to-r from-brand-yellow to-brand-deep transition group-hover:scale-x-100" />
                                <div className="flex items-start gap-4">
                                    <div
                                        className="public-overview-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-deep text-white motion-safe:animate-[quick-icon-float_4.2s_ease-in-out_infinite]"
                                        style={{ animationDelay: `${index * 0.35}s` }}
                                    >
                                        <Icon size={25} />
                                    </div>
                                    <div>
                                        <h2 className="text-lg font-semibold text-brand-charcoal">
                                            {card.title}
                                        </h2>
                                        <p className="mt-2 text-sm font-normal leading-6 text-brand-graphite">
                                            {card.body}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </section>
    );
}
