import {
    AccreditationCard,
    ProfileHero,
    ProfileShell,
} from '@/Components/Profile/ProfileComponents';
import { ProfilePageProps } from '@/types/profile';

export default function AccreditationPage({
    locale,
    seo,
    current,
}: ProfilePageProps & { current?: Record<string, string> | null }) {
    return (
        <ProfileShell locale={locale} seo={seo}>
            <ProfileHero
                locale={locale}
                title={locale === 'id' ? 'Akreditasi' : 'Accreditation'}
                description={
                    locale === 'id'
                        ? 'Status akreditasi Program Studi Informatika.'
                        : 'Accreditation status of the Informatics Study Program.'
                }
            />
            <div className="public-section mx-auto max-w-5xl px-4 py-10 sm:px-6">
                <AccreditationCard current={current} locale={locale} />
            </div>
        </ProfileShell>
    );
}
