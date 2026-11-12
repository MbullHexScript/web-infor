import { EmptyState } from '@/Components/Public/EmptyState';
import { Locale, LocalizedRecord } from '@/types/homepage';

export function ResearchSection({ items, locale }: { items: LocalizedRecord[]; locale: Locale }) {
    if (items.length === 0) {
        return (
            <EmptyState
                title={
                    locale === 'id'
                        ? 'Data riset sedang disiapkan'
                        : 'Research data is being prepared'
                }
                description={
                    locale === 'id'
                        ? 'Penelitian, pengabdian, publikasi, produk digital, dan HKI akan tampil setelah tersedia sebagai data published.'
                        : 'Research, community service, publications, digital products, and IP will appear after published data is available.'
                }
            />
        );
    }

    return <div className="grid gap-4 lg:grid-cols-3" />;
}
