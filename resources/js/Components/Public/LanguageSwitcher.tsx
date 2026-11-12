import { Link } from '@inertiajs/react';
import { Languages } from 'lucide-react';
import { Locale } from '@/types/homepage';

export function LanguageSwitcher({ locale }: { locale: Locale }) {
    const target = locale === 'id' ? '/en' : '/id';

    return (
        <Link
            href={target}
            className="hover:bg-brand-lavender/20 inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-sm)] px-3 text-sm font-bold text-brand-graphite transition hover:text-brand-deep dark:text-slate-200"
            aria-label={locale === 'id' ? 'Switch to English' : 'Ganti ke Bahasa Indonesia'}
        >
            <Languages size={17} />
            {locale === 'id' ? 'EN' : 'ID'}
        </Link>
    );
}
