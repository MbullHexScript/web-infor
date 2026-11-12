import { EmptyState } from '@/Components/Public/EmptyState';
import {
    InformationHero,
    InformationShell,
    SearchResultGroup,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import { InformationPageProps, SearchResult } from '@/types/information';

export default function GlobalSearchPage({
    locale,
    seo,
    filters,
    results,
}: InformationPageProps & {
    filters: Record<string, string>;
    results: {
        query: string;
        groups: Record<string, SearchResult[]>;
    };
}) {
    const groups = Object.entries(results.groups);
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero
                locale={locale}
                title={locale === 'en' ? 'Search' : 'Pencarian'}
                description={
                    filters.q
                        ? `${locale === 'en' ? 'Results for' : 'Hasil untuk'} "${filters.q}"`
                        : undefined
                }
            />
            <SectionBand title={locale === 'en' ? 'Results' : 'Hasil'}>
                {groups.length === 0 ? (
                    <EmptyState
                        title={locale === 'en' ? 'No results' : 'Tidak ada hasil'}
                        description={
                            locale === 'en' ? 'Try another keyword.' : 'Coba kata kunci lain.'
                        }
                    />
                ) : (
                    <div className="grid gap-5">
                        {groups.map(([type, items]) => (
                            <SearchResultGroup key={type} title={type} items={items} />
                        ))}
                    </div>
                )}
            </SectionBand>
        </InformationShell>
    );
}
