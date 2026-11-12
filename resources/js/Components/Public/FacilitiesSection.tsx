import { EmptyState } from '@/Components/Public/EmptyState';
import { Locale, LocalizedRecord } from '@/types/homepage';

export function FacilitiesSection({ items, locale }: { items: LocalizedRecord[]; locale: Locale }) {
    if (items.length === 0) {
        return (
            <EmptyState
                title={
                    locale === 'id'
                        ? 'Fasilitas sedang diinventarisasi'
                        : 'Facilities are being inventoried'
                }
                description={
                    locale === 'id'
                        ? 'Foto dan deskripsi fasilitas resmi akan tampil setelah tersedia dari admin.'
                        : 'Official facility photos and descriptions will appear after admin entry.'
                }
            />
        );
    }

    return <div className="grid gap-4 md:grid-cols-3" />;
}
