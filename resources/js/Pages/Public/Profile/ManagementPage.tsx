import { ManagementTree, ProfileHero, ProfileShell } from '@/Components/Profile/ProfileComponents';
import { ManagementPosition, ProfilePageProps } from '@/types/profile';

export default function ManagementPage({
    locale,
    seo,
    positions,
}: ProfilePageProps & { positions: ManagementPosition[] }) {
    return (
        <ProfileShell locale={locale} seo={seo}>
            <ProfileHero
                locale={locale}
                title={locale === 'id' ? 'Struktur Pengelola' : 'Management Structure'}
                description={
                    locale === 'id'
                        ? 'Struktur pengelola Program Studi Informatika.'
                        : 'Management structure of the Informatics Study Program.'
                }
            />
            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6">
                <ManagementTree positions={positions} locale={locale} />
            </div>
        </ProfileShell>
    );
}
