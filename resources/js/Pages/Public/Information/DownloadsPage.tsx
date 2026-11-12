import { EmptyState } from '@/Components/Public/EmptyState';
import {
    DocumentCard,
    FilterBar,
    InformationHero,
    InformationShell,
    ListingPage,
    Pagination,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import { DocumentItem, InformationPageProps, Paginated } from '@/types/information';

export default function DownloadsPage({
    locale,
    seo,
    filters,
    categories,
    documents,
}: InformationPageProps & {
    filters: Record<string, string>;
    categories: Array<{ slug: string; name: string }>;
    documents: Paginated<DocumentItem>;
}) {
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero
                locale={locale}
                title={locale === 'en' ? 'Download Center' : 'Pusat Unduhan'}
            />
            <SectionBand title={locale === 'en' ? 'Public Documents' : 'Dokumen Publik'}>
                <FilterBar locale={locale} filters={filters} categories={categories} />
                <ListingPage
                    items={documents}
                    render={(item) => <DocumentCard key={item.id} item={item} locale={locale} />}
                    empty={<EmptyState title="Empty" description="No documents found." />}
                />
                <Pagination data={documents} />
            </SectionBand>
        </InformationShell>
    );
}
