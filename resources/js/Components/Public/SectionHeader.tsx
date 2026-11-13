type SectionHeaderProps = {
    eyebrow?: string;
    title: string;
    subtitle?: string | null;
    align?: 'left' | 'center';
};

export function SectionHeader({ eyebrow, title, subtitle, align = 'left' }: SectionHeaderProps) {
    return (
        <div className={align === 'center' ? 'mx-auto max-w-3xl text-center' : 'max-w-3xl'}>
            {eyebrow && (
                <p className="text-xs font-bold uppercase tracking-[0.18em] text-brand-deep dark:text-brand-lavender">
                    {eyebrow}
                </p>
            )}
            <h2 className="public-section-title mt-3 font-heading text-3xl font-bold tracking-normal text-brand-charcoal dark:text-white md:text-4xl">
                {title}
            </h2>
            {subtitle && (
                <p className="public-section-description mt-4 text-base leading-7 text-brand-graphite dark:text-slate-300">
                    {subtitle}
                </p>
            )}
        </div>
    );
}
