import { NavbarBrand } from '@/Components/Layout/NavbarBrand';
import { ThemeToggle } from '@/Components/Layout/ThemeToggle';
import { DesktopNavigation } from '@/Components/Public/DesktopNavigation';
import { GlobalSearchButton } from '@/Components/Public/GlobalSearchButton';
import { LanguageSwitcher } from '@/Components/Public/LanguageSwitcher';
import { MobileDrawer } from '@/Components/Public/MobileDrawer';
import { Locale } from '@/types/homepage';
import { Menu } from 'lucide-react';
import { useEffect, useState } from 'react';

export function Navbar({ locale, hasTopBar = true }: { locale: Locale; hasTopBar?: boolean }) {
    const [scrolled, setScrolled] = useState(false);
    const [drawerOpen, setDrawerOpen] = useState(false);

    useEffect(() => {
        const handleScroll = () => setScrolled(window.scrollY > 20);
        handleScroll();
        window.addEventListener('scroll', handleScroll, { passive: true });

        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    return (
        <>
            <header
                className={`border-brand-silver/80 fixed inset-x-0 top-0 z-50 border-b bg-white/95 shadow-sm backdrop-blur-2xl transition-all duration-300 dark:border-white/10 dark:bg-[#11151a]/95 ${
                    hasTopBar ? 'lg:top-9' : ''
                }`}
            >
                <div
                    className={`mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 transition-all duration-300 sm:px-6 ${
                        scrolled ? 'h-16' : 'h-[4.75rem]'
                    }`}
                >
                    <NavbarBrand compact={scrolled} />
                    <DesktopNavigation locale={locale} />
                    <div className="flex items-center gap-2">
                        <div className="hidden sm:block">
                            <GlobalSearchButton
                                label={locale === 'id' ? 'Buka pencarian' : 'Open search'}
                            />
                        </div>
                        <div className="hidden sm:block">
                            <LanguageSwitcher locale={locale} />
                        </div>
                        <div className="hidden sm:block">
                            <ThemeToggle />
                        </div>
                        <button
                            type="button"
                            className="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-white/70 bg-white/70 shadow-sm backdrop-blur dark:border-white/10 dark:bg-white/10 lg:hidden"
                            onClick={() => setDrawerOpen(true)}
                            aria-label={locale === 'id' ? 'Buka menu' : 'Open menu'}
                            aria-expanded={drawerOpen}
                        >
                            <Menu size={20} />
                        </button>
                    </div>
                </div>
            </header>
            <MobileDrawer locale={locale} open={drawerOpen} onClose={() => setDrawerOpen(false)} />
        </>
    );
}
