import {
    FilterBar,
    Pagination,
    ProductCard,
    ResearchHero,
    ResearchShell,
    SectionBand,
} from '@/Components/Research/ResearchComponents';
import { Paginated } from '@/types/information';
import { Product, ResearchPageProps } from '@/types/research';

export default function ProductsPage({
    locale,
    seo,
    filters,
    items,
}: ResearchPageProps & { filters: Record<string, string>; items: Paginated<Product> }) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={locale === 'en' ? 'Products and Applications' : 'Produk dan Aplikasi'}
            />
            <SectionBand title={locale === 'en' ? 'Products' : 'Produk'}>
                <FilterBar locale={locale} filters={filters} />
                <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    {items.data.map((item) => (
                        <ProductCard key={item.id} item={item} locale={locale} />
                    ))}
                </div>
                <Pagination data={items} />
            </SectionBand>
        </ResearchShell>
    );
}
