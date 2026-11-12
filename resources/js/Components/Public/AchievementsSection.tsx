import { EmptyState } from '@/Components/Public/EmptyState';
import { Locale, LocalizedRecord } from '@/types/homepage';

export function AchievementsSection({
    items,
    locale,
}: {
    items: LocalizedRecord[];
    locale: Locale;
}) {
    if (items.length === 0) {
        return (
            <EmptyState
                title={
                    locale === 'id'
                        ? 'Prestasi terverifikasi belum tersedia'
                        : 'Verified achievements are not available yet'
                }
                description={
                    locale === 'id'
                        ? 'Prestasi mahasiswa akan ditampilkan setelah data published dan terverifikasi.'
                        : 'Student achievements will appear after published and verified data is available.'
                }
            />
        );
    }

    return <div className="grid gap-4 md:grid-cols-3" />;
}
