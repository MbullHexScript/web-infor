import { ResearchHero, ResearchShell, SectionBand } from '@/Components/Research/ResearchComponents';
import { CommunityService, ResearchPageProps } from '@/types/research';

export default function CommunityServiceDetailPage({
    locale,
    seo,
    item,
}: ResearchPageProps & { item: CommunityService }) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={item.title}
                description={item.summary ?? undefined}
            />
            <SectionBand title={locale === 'en' ? 'Activity Details' : 'Detail Kegiatan'}>
                <div className="public-card public-prose max-w-3xl rounded-md border border-brand-silver bg-white p-5 leading-7 dark:border-slate-700 dark:bg-[#1c2128]">
                    <p>
                        <strong>{locale === 'en' ? 'Location' : 'Lokasi'}:</strong>{' '}
                        {item.location ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Lead' : 'Ketua'}:</strong> {item.lead ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Beneficiaries' : 'Sasaran'}:</strong>{' '}
                        {item.beneficiaries ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Outputs' : 'Luaran'}:</strong>{' '}
                        {item.outputs ?? '-'}
                    </p>
                </div>
            </SectionBand>
        </ResearchShell>
    );
}
