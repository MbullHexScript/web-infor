import {
    Grid,
    JournalCard,
    ResearchHero,
    ResearchShell,
    SectionBand,
} from '@/Components/Research/ResearchComponents';
import { Journal, ResearchPageProps } from '@/types/research';

export default function JournalsLandingPage({
    locale,
    seo,
    items,
}: ResearchPageProps & { items: Journal[] }) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={locale === 'en' ? 'Informatics Journal Hub' : 'Rumah Jurnal Informatika'}
                description={
                    locale === 'en'
                        ? 'Advancing high-quality scholarly publication in informatics, digital innovation, and technology-driven community empowerment.'
                        : 'Mendorong publikasi ilmiah berkualitas dalam bidang informatika, inovasi digital, dan pemberdayaan masyarakat berbasis teknologi.'
                }
            />
            <SectionBand title={locale === 'en' ? 'Journals' : 'Jurnal'}>
                <Grid
                    items={items}
                    empty="No journals."
                    render={(item) => <JournalCard key={item.id} item={item} locale={locale} />}
                />
            </SectionBand>
        </ResearchShell>
    );
}
