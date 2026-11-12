import { NavbarBrand } from '@/Components/Layout/NavbarBrand';
import { ThemeToggle } from '@/Components/Layout/ThemeToggle';
import { Link } from '@inertiajs/react';
import { Menu, Search, X } from 'lucide-react';
import { PropsWithChildren, useState } from 'react';

const navigation = ['Profil', 'Akademik', 'Riset & Inovasi', 'Kemahasiswaan', 'Informasi', 'PMB'];

export function PublicLayout({ children }: PropsWithChildren) {
    const [open, setOpen] = useState(false);

    return (
        <div className="min-h-screen bg-brand-soft text-brand-charcoal dark:bg-[#11151a] dark:text-slate-50">
            <a
                href="#content"
                className="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:text-brand-deep"
            >
                Lewati ke konten utama
            </a>
            <div className="border-brand-silver/80 hidden border-b bg-white/80 text-xs text-brand-graphite backdrop-blur dark:border-slate-700 dark:bg-[#1c2128]/80 dark:text-slate-300 lg:block">
                <div className="mx-auto flex max-w-7xl justify-between px-6 py-2">
                    <div>UIN SMH Banten · Fakultas Sains dan Teknologi</div>
                    <div className="flex gap-5">
                        <a href="https://uinbanten.ac.id/">UIN SMH Banten</a>
                        <a href="https://fsainsuinbanten.my.id/">Fakultas Sains dan Teknologi</a>
                        <a href="#">SIAKAD</a>
                        <a href="#">E-Learning</a>
                        <a href="#">Perpustakaan</a>
                        <a href="#">PMB</a>
                    </div>
                </div>
            </div>
            <header className="border-brand-silver/80 sticky top-0 z-40 border-b bg-white/90 backdrop-blur dark:border-slate-700 dark:bg-[#1c2128]/90">
                <div className="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6">
                    <NavbarBrand />
                    <nav className="hidden items-center gap-1 lg:flex" aria-label="Navigasi utama">
                        <Link
                            href="/id"
                            className="rounded-md px-3 py-2 text-sm font-semibold text-brand-deep dark:text-brand-lavender"
                        >
                            Beranda
                        </Link>
                        {navigation.map((item) => (
                            <button
                                key={item}
                                type="button"
                                className="hover:bg-brand-lavender/20 rounded-md px-3 py-2 text-sm font-medium text-brand-graphite hover:text-brand-deep dark:text-slate-300"
                            >
                                {item}
                            </button>
                        ))}
                    </nav>
                    <div className="flex items-center gap-2">
                        <button
                            className="hidden h-11 w-11 items-center justify-center rounded-[var(--radius-sm)] border border-brand-silver bg-white text-brand-graphite transition hover:border-brand-purple hover:text-brand-deep dark:bg-[#1c2128] dark:text-slate-200 sm:inline-flex"
                            aria-label="Buka pencarian"
                        >
                            <Search size={18} />
                        </button>
                        <Link
                            href="/en"
                            className="hidden rounded-md px-3 py-2 text-sm font-semibold text-brand-graphite hover:text-brand-deep dark:text-slate-200 sm:block"
                        >
                            EN
                        </Link>
                        <ThemeToggle />
                        <button
                            type="button"
                            className="inline-flex h-11 w-11 items-center justify-center rounded-[var(--radius-sm)] border border-brand-silver lg:hidden"
                            onClick={() => setOpen((value) => !value)}
                            aria-label="Buka menu"
                            aria-expanded={open}
                        >
                            {open ? <X size={20} /> : <Menu size={20} />}
                        </button>
                    </div>
                </div>
                {open && (
                    <div className="border-t border-brand-silver bg-white px-4 py-4 dark:border-slate-700 dark:bg-[#1c2128] lg:hidden">
                        <nav className="grid gap-2" aria-label="Navigasi mobile">
                            {['Beranda', ...navigation].map((item) => (
                                <Link
                                    key={item}
                                    href="/id"
                                    className="hover:bg-brand-lavender/20 rounded-md px-3 py-3 text-sm font-semibold"
                                >
                                    {item}
                                </Link>
                            ))}
                        </nav>
                    </div>
                )}
            </header>
            <main id="content">{children}</main>
            <footer className="border-t border-brand-silver bg-white py-10 dark:border-slate-700 dark:bg-[#1c2128]">
                <div className="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[1.4fr_1fr]">
                    <div>
                        <NavbarBrand />
                        <p className="mt-4 max-w-2xl text-sm leading-6 text-brand-graphite dark:text-slate-300">
                            Program Studi Informatika, Fakultas Sains dan Teknologi, UIN Sultan
                            Maulana Hasanuddin Banten. Empowering Digital Innovation with Islamic
                            Values.
                        </p>
                    </div>
                    <div className="text-sm text-brand-graphite dark:text-slate-300">
                        <div className="font-semibold text-brand-charcoal dark:text-white">
                            Kontak
                        </div>
                        <a className="mt-2 block" href="mailto:informatika@uinbanten.ac.id">
                            informatika@uinbanten.ac.id
                        </a>
                        <a className="mt-2 block" href="https://hmps-pink.vercel.app/">
                            HMPS Informatika
                        </a>
                    </div>
                </div>
            </footer>
        </div>
    );
}
