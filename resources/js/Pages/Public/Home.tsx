import { PublicLayout } from '@/Components/Layout/PublicLayout';
import { Head, Link } from '@inertiajs/react';
import { ArrowRight, BookOpen, Cpu, Database, Network, Shield, Sparkles } from 'lucide-react';

type HomeProps = {
    locale: 'id' | 'en';
};

const fields = [
    ['Artificial Intelligence', Sparkles],
    ['Data Science', Database],
    ['Software Engineering', Cpu],
    ['Cybersecurity', Shield],
    ['Computer Network', Network],
    ['Internet of Things', Cpu],
] as const;

export default function Home({ locale }: HomeProps) {
    const isEnglish = locale === 'en';

    return (
        <PublicLayout>
            <Head title={isEnglish ? 'Informatics Study Program' : 'Program Studi Informatika'} />
            <section className="relative overflow-hidden border-b border-brand-silver bg-white dark:border-slate-700 dark:bg-[#11151a]">
                <div className="absolute inset-0 opacity-40 [background-image:linear-gradient(90deg,rgba(143,114,183,.16)_1px,transparent_1px),linear-gradient(rgba(143,114,183,.12)_1px,transparent_1px)] [background-size:42px_42px]" />
                <div className="bg-brand-yellow/20 absolute right-[-8rem] top-16 h-72 w-72 rounded-full blur-3xl" />
                <div className="bg-brand-lavender/40 absolute bottom-[-10rem] left-[-7rem] h-80 w-80 rounded-full blur-3xl" />
                <div className="relative mx-auto grid min-h-[calc(100vh-8rem)] max-w-7xl items-center gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[1.05fr_.95fr]">
                    <div>
                        <p className="text-sm font-bold uppercase tracking-[0.18em] text-brand-deep dark:text-brand-lavender">
                            Fakultas Sains dan Teknologi
                        </p>
                        <p className="mt-2 text-sm font-semibold uppercase tracking-[0.14em] text-brand-graphite dark:text-slate-300">
                            UIN Sultan Maulana Hasanuddin Banten
                        </p>
                        <h1 className="mt-6 font-heading text-4xl font-bold tracking-normal text-brand-charcoal dark:text-white sm:text-5xl lg:text-6xl">
                            Program Studi Informatika
                        </h1>
                        <p className="mt-5 max-w-2xl text-lg font-semibold text-brand-deep dark:text-brand-lavender">
                            Empowering Digital Innovation with Islamic Values
                        </p>
                        <p className="mt-5 max-w-2xl text-base leading-8 text-brand-graphite dark:text-slate-300">
                            {isEnglish
                                ? 'Preparing professionals in artificial intelligence, software engineering, data science, computer networks, and cybersecurity with Islamic values.'
                                : 'Membangun generasi profesional di bidang kecerdasan buatan, rekayasa perangkat lunak, data science, jaringan komputer, dan keamanan siber dengan landasan nilai-nilai keislaman.'}
                        </p>
                        <div className="mt-8 flex flex-wrap gap-3">
                            <Link
                                href={`/${locale}/profil/tentang`}
                                className="inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-sm)] bg-brand-deep px-5 py-3 text-sm font-bold text-white shadow-[var(--shadow-md)]"
                            >
                                Jelajahi Informatika <ArrowRight size={18} />
                            </Link>
                            <Link
                                href={`/${locale}/akademik/kurikulum`}
                                className="inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-sm)] border border-brand-purple px-5 py-3 text-sm font-bold text-brand-deep dark:text-brand-lavender"
                            >
                                <BookOpen size={18} /> Lihat Kurikulum
                            </Link>
                        </div>
                    </div>
                    <div className="border-brand-lavender/60 bg-brand-soft/80 relative min-h-[24rem] rounded-[var(--radius-lg)] border p-6 shadow-[var(--shadow-glow)] dark:border-slate-700 dark:bg-[#1c2128]">
                        <div className="bg-brand-yellow/60 absolute left-1/2 top-10 h-40 w-2 -translate-x-1/2 rounded-full shadow-[0_0_48px_rgba(242,201,76,.35)]" />
                        <div className="border-brand-purple/40 absolute left-1/2 top-20 h-48 w-48 -translate-x-1/2 rounded-full border" />
                        <div className="grid h-full grid-cols-2 gap-4">
                            {fields.map(([field, Icon]) => (
                                <div
                                    key={field}
                                    className="rounded-[var(--radius-md)] border border-white/70 bg-white/70 p-4 shadow-[var(--shadow-sm)] backdrop-blur dark:border-slate-700 dark:bg-[#222832]"
                                >
                                    <Icon
                                        className="text-brand-deep dark:text-brand-lavender"
                                        size={22}
                                    />
                                    <div className="mt-4 text-sm font-bold">{field}</div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>
            <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6">
                <div className="grid gap-4 md:grid-cols-3">
                    {['Statistik belum diverifikasi', 'Kurikulum OBE 2026', 'Rumah Jurnal'].map(
                        (item) => (
                            <div
                                key={item}
                                className="rounded-[var(--radius-md)] border border-brand-silver bg-white p-5 shadow-[var(--shadow-sm)] dark:border-slate-700 dark:bg-[#1c2128]"
                            >
                                <div className="text-sm font-bold text-brand-deep dark:text-brand-lavender">
                                    {item}
                                </div>
                                <p className="mt-2 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                                    Placeholder aman untuk fondasi fase 1. Konten resmi akan
                                    dikelola melalui dashboard admin pada fase berikutnya.
                                </p>
                            </div>
                        ),
                    )}
                </div>
            </section>
        </PublicLayout>
    );
}
