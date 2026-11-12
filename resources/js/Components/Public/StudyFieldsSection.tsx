import { Locale, StudyField } from '@/types/homepage';
import { Code2, Cpu, Database, LucideIcon, Network, ShieldCheck, Sparkles } from 'lucide-react';

const icons: Record<string, LucideIcon> = { Code2, Cpu, Database, Network, ShieldCheck, Sparkles };

export function StudyFieldsSection({ fields, locale }: { fields: StudyField[]; locale: Locale }) {
    return (
        <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            {fields.map((field, index) => {
                const Icon = icons[field.icon] ?? Sparkles;
                return (
                    <article
                        key={field.key}
                        className="group relative overflow-hidden rounded-[var(--radius-md)] border border-brand-silver bg-white p-5 shadow-[var(--shadow-sm)] transition hover:-translate-y-1 hover:border-brand-purple dark:border-slate-700 dark:bg-[#1c2128]"
                    >
                        <div className="text-brand-lavender/20 absolute right-4 top-2 font-mono text-6xl font-bold">
                            {String(index + 1).padStart(2, '0')}
                        </div>
                        <div className="bg-brand-lavender/20 relative flex h-12 w-12 items-center justify-center rounded-[var(--radius-sm)] text-brand-deep dark:text-brand-lavender">
                            <Icon size={23} />
                        </div>
                        <h3 className="relative mt-6 text-lg font-bold text-brand-charcoal dark:text-white">
                            {locale === 'id' ? field.name_id : field.name_en}
                        </h3>
                        <p className="relative mt-3 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                            {locale === 'id' ? field.description_id : field.description_en}
                        </p>
                    </article>
                );
            })}
        </div>
    );
}
