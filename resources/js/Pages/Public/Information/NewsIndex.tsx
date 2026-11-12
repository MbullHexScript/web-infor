import { EmptyState } from '@/Components/Public/EmptyState';
import {
    FilterBar,
    InformationHero,
    InformationShell,
    NewsGrid,
    Pagination,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import { InformationPageProps, NewsItem, Paginated } from '@/types/information';

export default function NewsIndex({
    locale,
    seo,
    filters,
    categories,
    news,
}: InformationPageProps & {
    filters: Record<string, string>;
    categories: Array<{ slug: string; name: string }>;
    news: Paginated<NewsItem>;
}) {
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero
                locale={locale}
                title={locale === 'en' ? 'News' : 'Berita'}
                description={
                    locale === 'en'
                        ? 'Curated updates from the Informatics Study Program.'
                        : 'Kabar terkurasi dari Program Studi Informatika.'
                }
            />
            <SectionBand title={locale === 'en' ? 'News Archive' : 'Arsip Berita'}>
                <FilterBar locale={locale} filters={filters} categories={categories} />
                <NewsGrid items={news.data} locale={locale} />
                {news.data.length === 0 && (
                    <EmptyState title="Empty" description="No matching news." />
                )}
                <Pagination data={news} />
            </SectionBand>
        </InformationShell>
    );
}
