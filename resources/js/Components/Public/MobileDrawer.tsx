import { ThemeToggle } from '@/Components/Layout/ThemeToggle';
import { LanguageSwitcher } from '@/Components/Public/LanguageSwitcher';
import { Locale } from '@/types/homepage';
import { Link } from '@inertiajs/react';
import { X } from 'lucide-react';
import { useEffect, useRef } from 'react';

type MobileDrawerProps = {
    locale: Locale;
    open: boolean;
    onClose: () => void;
};

const links = [
    ['Beranda', 'Home', '/id', '/en'],
    ['Profil', 'Profile', '/id/profil/tentang', '/en/profile/about'],
    ['Akademik', 'Academics', '/id/akademik/kurikulum', '/en/academics/curriculum'],
    ['Riset & Inovasi', 'Research', '/id/riset', '/en/research'],
    ['Kemahasiswaan', 'Student Life', '/id/kemahasiswaan', '/en/student-life'],
    ['Informasi', 'Information', '/id/informasi', '/en/information'],
    ['PMB', 'Admissions', 'https://pmb.uinbanten.ac.id/', 'https://pmb.uinbanten.ac.id/'],
] as const;

export function MobileDrawer({ locale, open, onClose }: MobileDrawerProps) {
    const panelRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!open) return;

        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        panelRef.current?.querySelector<HTMLElement>('button, a')?.focus();

        const handleKey = (event: KeyboardEvent) => {
            if (event.key === 'Escape') onClose();
            if (event.key !== 'Tab' || !panelRef.current) return;

            const focusables = panelRef.current.querySelectorAll<HTMLElement>(
                'a[href], button:not([disabled])',
            );
            const first = focusables[0];
            const last = focusables[focusables.length - 1];

            if (!first || !last) return;
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        };

        document.addEventListener('keydown', handleKey);

        return () => {
            document.body.style.overflow = previousOverflow;
            document.removeEventListener('keydown', handleKey);
        };
    }, [onClose, open]);

    if (!open) return null;

    return (
        <div className="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true">
            <button
                type="button"
                className="bg-brand-charcoal/55 absolute inset-0"
                onClick={onClose}
                aria-label={locale === 'id' ? 'Tutup menu' : 'Close menu'}
            />
            <div
                ref={panelRef}
                className="absolute right-0 top-0 flex h-dvh w-[min(22rem,88vw)] flex-col overflow-y-auto bg-white p-5 shadow-[var(--shadow-lg)] dark:bg-[#1c2128]"
            >
                <div className="flex items-center justify-between">
                    <div className="font-heading text-lg font-bold">INFORMATIKA</div>
                    <button
                        type="button"
                        className="inline-flex h-11 w-11 items-center justify-center rounded-md border border-brand-silver dark:border-slate-700"
                        onClick={onClose}
                        aria-label={locale === 'id' ? 'Tutup menu' : 'Close menu'}
                    >
                        <X size={20} />
                    </button>
                </div>
                <nav className="mt-8 grid gap-2" aria-label="Navigasi mobile">
                    {links.map(([idLabel, enLabel, idHref, enHref]) => {
                        const href = locale === 'id' ? idHref : enHref;
                        const external = href.startsWith('http');

                        if (external) {
                            return (
                                <a
                                    key={idHref}
                                    href={href}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="hover:bg-brand-lavender/20 rounded-md px-3 py-3 text-base font-semibold text-brand-graphite dark:text-slate-200"
                                    onClick={onClose}
                                >
                                    {locale === 'id' ? idLabel : enLabel}
                                </a>
                            );
                        }

                        return (
                            <Link
                                key={idHref}
                                href={href}
                                className="hover:bg-brand-lavender/20 rounded-md px-3 py-3 text-base font-semibold text-brand-graphite dark:text-slate-200"
                                onClick={onClose}
                            >
                                {locale === 'id' ? idLabel : enLabel}
                            </Link>
                        );
                    })}
                </nav>
                <div className="mt-auto flex items-center justify-between border-t border-brand-silver pt-5 dark:border-slate-700">
                    <LanguageSwitcher locale={locale} />
                    <ThemeToggle />
                </div>
            </div>
        </div>
    );
}
