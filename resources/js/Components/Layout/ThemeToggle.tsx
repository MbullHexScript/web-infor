import { Moon, Sun } from 'lucide-react';
import { useEffect, useState } from 'react';

type Theme = 'light' | 'dark';

export function ThemeToggle() {
    const [theme, setTheme] = useState<Theme>(() =>
        document.documentElement.classList.contains('dark') ? 'dark' : 'light',
    );

    useEffect(() => {
        document.documentElement.classList.toggle('dark', theme === 'dark');
        localStorage.setItem('theme', theme);
    }, [theme]);

    return (
        <button
            type="button"
            onClick={() => setTheme((current) => (current === 'dark' ? 'light' : 'dark'))}
            className="inline-flex h-11 w-11 items-center justify-center rounded-[var(--radius-sm)] border border-brand-silver bg-white text-brand-graphite transition hover:border-brand-purple hover:text-brand-deep dark:bg-[#1c2128] dark:text-slate-200"
            aria-label={theme === 'dark' ? 'Aktifkan light mode' : 'Aktifkan dark mode'}
        >
            {theme === 'dark' ? <Sun size={18} /> : <Moon size={18} />}
        </button>
    );
}
