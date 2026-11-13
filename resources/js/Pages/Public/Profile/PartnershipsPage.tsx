import { PartnersGrid, ProfileHero, ProfileShell } from '@/Components/Profile/ProfileComponents';
import { Partner, ProfilePageProps } from '@/types/profile';

export default function PartnershipsPage({
    locale,
    seo,
    partners,
}: ProfilePageProps & { partners: Partner[] }) {
    return (
        <ProfileShell locale={locale} seo={seo}>
            <ProfileHero
                locale={locale}
                title={locale === 'id' ? 'Kerja Sama' : 'Partnerships'}
                description={
                    locale === 'id'
                        ? 'Kerja sama dan mitra Program Studi Informatika.'
                        : 'Partnerships of the Informatics Study Program.'
                }
            />
            <div className="public-section mx-auto max-w-7xl px-4 py-10 sm:px-6">
                <PartnersGrid partners={partners} />
            </div>
        </ProfileShell>
    );
}
