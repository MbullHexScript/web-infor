import { Inbox } from 'lucide-react';

export function EmptyState({ title, description }: { title: string; description: string }) {
    return (
        <div className="rounded-[var(--radius-md)] border border-dashed border-brand-silver bg-white/70 p-6 text-sm text-brand-graphite dark:border-slate-700 dark:bg-[#1c2128]/70 dark:text-slate-300">
            <Inbox className="mb-3 text-brand-deep dark:text-brand-lavender" size={22} />
            <div className="font-semibold text-brand-charcoal dark:text-white">{title}</div>
            <p className="mt-2 leading-6">{description}</p>
        </div>
    );
}
