export function LoadingSkeleton() {
    return (
        <div className="public-card animate-pulse rounded-[var(--radius-md)] border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="h-4 w-1/2 rounded bg-brand-silver dark:bg-slate-700" />
            <div className="mt-4 h-3 w-full rounded bg-brand-silver dark:bg-slate-700" />
            <div className="mt-2 h-3 w-2/3 rounded bg-brand-silver dark:bg-slate-700" />
        </div>
    );
}
