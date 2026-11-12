import { TopLink } from '@/types/homepage';
import { ExternalLink } from 'lucide-react';

export function TopBar({ links }: { links: TopLink[] }) {
    const [primary, ...secondary] = links;

    if (links.length === 0) return null;

    return (
        <div className="fixed inset-x-0 top-0 z-50 hidden h-9 border-b border-white/30 bg-brand-charcoal text-xs text-white/85 dark:border-slate-800 lg:block">
            <div className="mx-auto flex h-full max-w-7xl items-center justify-between px-6">
                <div className="flex items-center gap-4">
                    <TopBarLink link={primary} />
                    <span className="h-3 w-px bg-white/30" />
                    <TopBarLink link={secondary[0]} />
                </div>
                <div className="flex items-center gap-4">
                    {secondary.slice(1).map((link) => (
                        <TopBarLink key={link.label} link={link} />
                    ))}
                </div>
            </div>
        </div>
    );
}

function TopBarLink({ link }: { link?: TopLink }) {
    if (!link) return null;

    return (
        <a
            href={link.url}
            target={link.external ? '_blank' : undefined}
            rel={link.external ? 'noopener noreferrer' : undefined}
            className="inline-flex items-center gap-1 rounded-sm hover:text-brand-yellow focus-visible:outline-brand-yellow"
        >
            {link.label}
            {link.external && <ExternalLink size={12} aria-hidden />}
        </a>
    );
}
