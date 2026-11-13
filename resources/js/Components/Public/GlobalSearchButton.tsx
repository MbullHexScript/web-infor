import { Search } from 'lucide-react';
import { FormEvent, useEffect, useState } from 'react';

export function GlobalSearchButton({ label }: { label: string }) {
    const [open, setOpen] = useState(false);
    const [query, setQuery] = useState('');
    const locale =
        typeof window !== 'undefined' && window.location.pathname.startsWith('/en') ? 'en' : 'id';

    useEffect(() => {
        const onKey = (event: KeyboardEvent) => {
            const target = event.target as HTMLElement | null;
            const typing = target?.tagName === 'INPUT' || target?.tagName === 'TEXTAREA';
            if (
                (event.key === '/' && !typing) ||
                ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k')
            ) {
                event.preventDefault();
                setOpen(true);
            }
            if (event.key === 'Escape') setOpen(false);
        };
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, []);

    const submit = (event: FormEvent) => {
        event.preventDefault();
        window.location.href = `${locale === 'en' ? '/en/search' : '/id/pencarian'}?q=${encodeURIComponent(query)}`;
    };

    return (
        <>
            <button
                type="button"
                onClick={() => setOpen(true)}
                className="public-control inline-flex h-11 w-11 items-center justify-center rounded-[var(--radius-sm)] border border-brand-silver bg-white/80 text-brand-graphite transition hover:border-brand-purple hover:text-brand-deep dark:border-slate-700 dark:bg-[#1c2128] dark:text-slate-200"
                aria-label={label}
            >
                <Search size={18} />
            </button>
            {open && (
                <div
                    className="fixed inset-0 z-[80] bg-black/45 p-4 backdrop-blur-sm"
                    role="dialog"
                    aria-modal="true"
                >
                    <form
                        onSubmit={submit}
                        className="public-search-panel mx-auto mt-24 max-w-xl rounded-2xl bg-white p-4 shadow-[var(--shadow-lg)] dark:bg-[#1c2128]"
                    >
                        <label
                            htmlFor="global-search-overlay"
                            className="text-sm font-bold text-brand-charcoal dark:text-white"
                        >
                            {locale === 'en' ? 'Search the website' : 'Cari di website'}
                        </label>
                        <div className="mt-3 flex gap-2">
                            <input
                                id="global-search-overlay"
                                autoFocus
                                value={query}
                                onChange={(event) => setQuery(event.target.value)}
                                className="min-w-0 flex-1 rounded-md border border-brand-silver px-3 py-2 text-sm text-brand-charcoal"
                                placeholder={
                                    locale === 'en'
                                        ? 'Courses, news, documents...'
                                        : 'Mata kuliah, berita, dokumen...'
                                }
                            />
                            <button className="public-button-primary rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white">
                                {locale === 'en' ? 'Search' : 'Cari'}
                            </button>
                        </div>
                        <button
                            type="button"
                            onClick={() => setOpen(false)}
                            className="mt-3 text-sm font-bold text-brand-graphite dark:text-slate-300"
                        >
                            Esc
                        </button>
                    </form>
                </div>
            )}
        </>
    );
}
