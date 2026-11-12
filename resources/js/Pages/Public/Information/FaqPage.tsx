import {
    FaqAccordion,
    FilterBar,
    InformationHero,
    InformationShell,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import { FaqItem, InformationPageProps } from '@/types/information';

export default function FaqPage({
    locale,
    seo,
    filters,
    faqs,
}: InformationPageProps & {
    filters: Record<string, string>;
    categories: string[];
    faqs: FaqItem[];
}) {
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero locale={locale} title="FAQ" />
            <SectionBand title={locale === 'en' ? 'Questions' : 'Pertanyaan'}>
                <FilterBar locale={locale} filters={filters} />
                <FaqAccordion items={faqs} />
            </SectionBand>
        </InformationShell>
    );
}
