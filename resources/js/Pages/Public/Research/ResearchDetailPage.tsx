import {
    ResearchCard,
    ResearchHero,
    ResearchShell,
    SectionBand,
} from '@/Components/Research/ResearchComponents';
import { ResearchPageProps, ResearchProject } from '@/types/research';

export default function ResearchDetailPage({
    locale,
    seo,
    item,
    related,
}: ResearchPageProps & { item: ResearchProject; related: ResearchProject[] }) {
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero
                locale={locale}
                title={item.title}
                description={item.summary ?? undefined}
            />
            <SectionBand title={locale === 'en' ? 'Research Details' : 'Detail Penelitian'}>
                <div className="max-w-3xl rounded-md border border-brand-silver bg-white p-5 leading-7 dark:border-slate-700 dark:bg-[#1c2128]">
                    <p>
                        <strong>{locale === 'en' ? 'Lead' : 'Ketua'}:</strong> {item.lead ?? '-'}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Year' : 'Tahun'}:</strong> {item.year}
                    </p>
                    <p>
                        <strong>{locale === 'en' ? 'Output' : 'Luaran'}:</strong>{' '}
                        {item.output ?? '-'}
                    </p>
                    {item.team?.length ? (
                        <ul className="mt-3 list-disc pl-5">
                            {item.team.map((member) => (
                                <li key={member.name}>
                                    {member.name} {member.role ? `- ${member.role}` : ''}
                                </li>
                            ))}
                        </ul>
                    ) : null}
                </div>
            </SectionBand>
            <SectionBand title={locale === 'en' ? 'Related Research' : 'Penelitian Terkait'}>
                <div className="grid gap-5 md:grid-cols-3">
                    {related.map((row) => (
                        <ResearchCard key={row.id} item={row} locale={locale} />
                    ))}
                </div>
            </SectionBand>
        </ResearchShell>
    );
}
