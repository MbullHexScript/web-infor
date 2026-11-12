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
        <section className="relative isolate overflow-hidden bg-[#f8f9fb] text-brand-charcoal">
            <div className="relative overflow-hidden bg-[#120a23]">
                <div className="absolute inset-0 bg-[linear-gradient(105deg,rgba(8,9,24,.98)_0%,rgba(36,18,70,.97)_46%,rgba(73,42,116,.90)_100%)]" />
                <div className="absolute inset-0 bg-[radial-gradient(circle_at_72%_36%,rgba(124,43,216,.34),transparent_36%),radial-gradient(circle_at_20%_72%,rgba(251,195,31,.16),transparent_30%)]" />
                <div className="absolute inset-y-0 right-0 hidden w-[56%] overflow-hidden lg:block">
                    <div className="absolute inset-0 bg-[radial-gradient(circle_at_54%_46%,rgba(251,195,31,.18),transparent_24%),radial-gradient(circle_at_62%_42%,rgba(124,43,216,.42),transparent_38%),linear-gradient(90deg,rgba(18,10,35,.78)_0%,rgba(18,10,35,.42)_42%,rgba(18,10,35,.04)_100%)]" />
                    <div className="absolute inset-0 opacity-[.18] [background-image:linear-gradient(135deg,rgba(255,255,255,.22)_1px,transparent_1px)] [background-size:24px_24px]" />
                </div>
                <div className="pointer-events-none absolute inset-0 opacity-[.13] [background-image:linear-gradient(90deg,rgba(255,255,255,.30)_1px,transparent_1px),linear-gradient(rgba(255,255,255,.30)_1px,transparent_1px)] [background-size:54px_54px]" />
                <div className="border-white/18 pointer-events-none absolute right-[18%] top-[24%] hidden h-64 w-64 rounded-full border lg:block" />
                <div className="border-brand-yellow/25 pointer-events-none absolute right-[24%] top-[38%] hidden h-40 w-40 rounded-full border lg:block" />

                <div className="relative z-10 mx-auto grid max-w-7xl items-center gap-8 px-4 pb-28 pt-24 sm:px-6 sm:pb-32 sm:pt-28 lg:min-h-[620px] lg:grid-cols-[0.76fr_1.24fr] lg:gap-14 lg:pb-36 lg:pt-40">
                    <div className="max-w-[34rem]">
                        <Reveal>
                            <p className="text-sm font-black tracking-normal text-brand-yellow sm:text-base">
                                {isEnglish ? 'Welcome to the Website' : 'Selamat Datang di Website'}
                            </p>
                        </Reveal>
                        <Reveal delay={100}>
                            <h1 className="relative mt-4 max-w-[34rem] overflow-hidden font-heading text-[2.25rem] font-black leading-[1.08] tracking-normal text-white sm:text-[3rem] lg:text-[2.18rem] xl:text-[2.48rem]">
                                <span className="block">Program Studi Informatika</span>
                                <span className="text-white/92 mt-2 block text-[.62em] font-bold leading-tight">
                                    UIN Sultan Maulana Hasanuddin Banten
                                </span>
                                <span
                                    aria-hidden="true"
                                    className="pointer-events-none absolute inset-y-0 -left-1/4 w-1/4 skew-x-[-18deg] bg-gradient-to-r from-transparent via-white/80 to-transparent opacity-70 mix-blend-screen motion-safe:animate-[hero-title-sheen_5.2s_ease-in-out_infinite]"
                                />
                            </h1>
                        </Reveal>
                        <Reveal delay={200}>
                            <p className="mt-5 max-w-[34rem] text-base font-bold leading-7 text-brand-yellow sm:text-xl sm:leading-8 lg:text-lg">
                                {hero.tagline}
                            </p>
                        </Reveal>
                        <Reveal delay={300}>
                            <p className="mt-4 max-w-[34rem] text-sm font-medium leading-7 text-white/80 sm:text-base sm:leading-8">
                                {isEnglish ? hero.description_en : hero.description_id}
                            </p>
                        </Reveal>
                        <Reveal delay={400}>
                            <div className="mt-8 flex flex-wrap gap-4">
                                <Link
                                    href={isEnglish ? '/en/profile/about' : '/id/profil/tentang'}
                                    className="group inline-flex min-h-12 items-center gap-3 rounded-[var(--radius-sm)] bg-gradient-to-r from-[#7c2bd8] to-brand-deep px-6 py-3 text-sm font-black text-white shadow-[0_20px_50px_rgba(124,43,216,.38)] transition hover:-translate-y-0.5"
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
                                    className="inline-flex min-h-12 items-center gap-3 rounded-[var(--radius-sm)] border border-white/70 bg-white/5 px-6 py-3 text-sm font-black text-white shadow-[0_16px_40px_rgba(0,0,0,.18)] backdrop-blur transition hover:-translate-y-0.5 hover:bg-white hover:text-brand-deep"
                                >
                                    <BookOpen size={18} />
                                    {isEnglish ? 'OBE Curriculum' : 'Kurikulum OBE'}
                                </Link>
                            </div>
                        </Reveal>
                    </div>

                    <Reveal delay={180}>
                        <div className="bg-white/8 relative min-h-[24rem] overflow-hidden rounded-[1.2rem] border border-white/20 shadow-[0_28px_90px_rgba(0,0,0,.24)] backdrop-blur-sm lg:min-h-[25rem]">
                            <div className="absolute inset-0 bg-[radial-gradient(circle_at_50%_48%,rgba(251,195,31,.18),transparent_23%),radial-gradient(circle_at_56%_44%,rgba(124,43,216,.42),transparent_40%)]" />
                            <div className="absolute inset-0 opacity-[.16] [background-image:linear-gradient(135deg,rgba(255,255,255,.28)_1px,transparent_1px)] [background-size:24px_24px]" />
                            <div className="border-white/28 absolute left-1/2 top-1/2 h-[22rem] w-[22rem] -translate-x-1/2 -translate-y-1/2 rounded-full border motion-safe:animate-[orbit-spin_52s_linear_infinite]" />
                            <div className="border-brand-yellow/35 absolute left-1/2 top-1/2 h-[14rem] w-[14rem] -translate-x-1/2 -translate-y-1/2 rounded-full border motion-safe:animate-[orbit-spin_38s_linear_infinite_reverse]" />
                            <div className="border-white/18 absolute left-[58%] top-[22%] h-20 w-20 rounded-full border" />
                            <div className="absolute right-6 top-6 flex gap-3">
                                <img
                                    src="/assets/brand/logo-uin-smh-banten-512.webp"
                                    alt="Logo UIN SMH Banten"
                                    className="bg-white/86 h-16 w-16 rounded-[var(--radius-sm)] border border-white/75 object-contain p-2 shadow-[0_18px_40px_rgba(0,0,0,.18)] backdrop-blur"
                                    width="64"
                                    height="64"
                                />
                                <img
                                    src="/assets/brand/logo-informatika-official-256.png"
                                    alt="Logo Program Studi Informatika"
                                    className="bg-white/86 h-16 w-16 rounded-full border border-white/75 object-contain p-1.5 shadow-[0_18px_40px_rgba(0,0,0,.18)] backdrop-blur"
                                    width="64"
                                    height="64"
                                />
                            </div>
                            <div className="bg-white/82 absolute left-1/2 top-1/2 z-10 flex h-44 w-44 -translate-x-[64%] -translate-y-1/2 items-center justify-center rounded-full border border-white/35 p-3 shadow-[0_24px_70px_rgba(0,0,0,.22)] backdrop-blur-xl sm:h-52 sm:w-52">
                                <img
                                    src="/assets/brand/logo-informatika-official-256.png"
                                    alt="Logo Program Studi Informatika"
                                    className="h-full w-full rounded-full object-contain motion-safe:animate-[hero-logo-float_5s_ease-in-out_infinite]"
                                    width="208"
                                    height="208"
                                />
                            </div>
                            <div className="bg-white/82 absolute left-1/2 top-1/2 z-10 flex h-32 w-32 -translate-y-1/2 translate-x-[34%] items-center justify-center rounded-[1rem] border border-white/35 p-4 shadow-[0_24px_70px_rgba(0,0,0,.20)] backdrop-blur-xl sm:h-40 sm:w-40">
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
                                            className="flex min-h-11 items-center justify-center gap-2 rounded-[var(--radius-sm)] border border-white/35 bg-white/10 px-3 text-xs font-black text-white backdrop-blur"
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
                <div className="border-brand-silver/80 grid overflow-hidden rounded-[1.1rem] border bg-white shadow-[0_24px_70px_rgba(23,27,33,.16)] md:grid-cols-2 xl:grid-cols-4">
                    {overviewCards.map((card, index) => {
                        const Icon = card.icon;
                        return (
                            <div
                                key={card.title}
                                className="border-brand-silver/80 hover:bg-brand-lavender/10 group relative min-h-36 p-6 transition md:border-r md:last:border-r-0"
                            >
                                <div className="absolute inset-x-6 top-0 h-1 origin-left scale-x-0 bg-gradient-to-r from-brand-yellow to-brand-deep transition group-hover:scale-x-100" />
                                <div className="flex items-start gap-4">
                                    <div
                                        className="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-brand-deep text-white shadow-[0_16px_38px_rgba(90,68,119,.26)] motion-safe:animate-[quick-icon-float_4.2s_ease-in-out_infinite]"
                                        style={{ animationDelay: `${index * 0.35}s` }}
                                    >
                                        <Icon size={25} />
                                    </div>
                                    <div>
                                        <h2 className="text-lg font-black text-brand-charcoal">
                                            {card.title}
                                        </h2>
                                        <p className="mt-2 text-sm font-medium leading-6 text-brand-graphite">
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
