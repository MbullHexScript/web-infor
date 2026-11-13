import { Footer } from '@/Components/Public/Footer';
import { Navbar } from '@/Components/Public/Navbar';
import { TopBar } from '@/Components/Public/TopBar';
import { Locale } from '@/types/homepage';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';

export default function Placeholder({ locale, path }: { locale: Locale; path: string }) {
    const isEnglish = locale === 'en';

    return (
        <div className="public-site min-h-screen bg-brand-soft dark:bg-[#11151a] dark:text-white">
            <Head title={isEnglish ? 'Page in preparation' : 'Halaman sedang disiapkan'} />
            <TopBar links={[]} />
            <Navbar locale={locale} hasTopBar={false} />
            <main className="mx-auto max-w-4xl px-4 py-20 pt-[calc(4.75rem+5rem)] sm:px-6">
                <p className="text-sm font-bold uppercase tracking-[0.18em] text-brand-deep dark:text-brand-lavender">
                    {path}
                </p>
                <h1 className="mt-4 font-heading text-4xl font-bold">
                    {isEnglish ? 'This page is being prepared' : 'Halaman ini sedang disiapkan'}
                </h1>
                <p className="mt-5 text-brand-graphite dark:text-slate-300">
                    {isEnglish
                        ? 'The content will be connected to the admin dashboard in the next implementation phase.'
                        : 'Konten halaman akan dihubungkan ke dashboard admin pada fase implementasi berikutnya.'}
                </p>
                <Link
                    href={`/${locale}`}
                    className="mt-8 inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-sm)] bg-brand-deep px-5 py-3 text-sm font-bold text-white"
                >
                    <ArrowLeft size={18} />
                    {isEnglish ? 'Back to Home' : 'Kembali ke Beranda'}
                </Link>
            </main>
            <Footer locale={locale} quickLinks={[]} links={{}} contact={{}} />
        </div>
    );
}
