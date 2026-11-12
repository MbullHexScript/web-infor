import {
    ExpertiseGroupCard,
    Grid,
    ResearchHero,
    ResearchShell,
    SectionBand,
} from '@/Components/Research/ResearchComponents';
import { ExpertiseGroup, ResearchPageProps } from '@/types/research';

export default function ExpertiseGroupsPage({
    locale,
    seo,
    items,
}: ResearchPageProps & { items: ExpertiseGroup[] }) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={locale === 'en' ? 'Expertise Groups' : 'Kelompok Keahlian'}
            />
            <SectionBand title={locale === 'en' ? 'Groups' : 'Kelompok'}>
                <Grid
                    items={items}
                    empty="No active groups yet."
                    render={(item) => <ExpertiseGroupCard key={item.id} item={item} />}
                />
            </SectionBand>
        </ResearchShell>
    );
}
