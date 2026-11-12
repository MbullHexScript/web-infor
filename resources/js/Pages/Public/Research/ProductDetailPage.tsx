import {
    ResearchHero,
    ResearchShell,
    SectionBand,
    TechnologyBadge,
} from '@/Components/Research/ResearchComponents';
import { Product, ResearchPageProps } from '@/types/research';

export default function ProductDetailPage({
    locale,
    seo,
    item,
}: ResearchPageProps & { item: Product }) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={item.title}
                description={item.summary ?? undefined}
            />
            <SectionBand title={locale === 'en' ? 'Product Details' : 'Detail Produk'}>
                <div className="max-w-3xl rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
                    <div className="flex flex-wrap gap-2">
                        {item.technologies.map((tech) => (
                            <TechnologyBadge key={tech} label={tech} />
                        ))}
                    </div>
                    <div className="mt-5 flex flex-wrap gap-3">
                        {item.demoUrl && (
                            <a
                                className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                                href={item.demoUrl}
                            >
                                Demo
                            </a>
                        )}
                        {item.repositoryUrl && (
                            <a
                                className="rounded-md border px-4 py-2 text-sm font-bold"
                                href={item.repositoryUrl}
                            >
                                Repository
                            </a>
                        )}
                        {item.documentationUrl && (
                            <a
                                className="rounded-md border px-4 py-2 text-sm font-bold"
                                href={item.documentationUrl}
                            >
                                Docs
                            </a>
                        )}
                    </div>
                </div>
            </SectionBand>
        </ResearchShell>
    );
}
