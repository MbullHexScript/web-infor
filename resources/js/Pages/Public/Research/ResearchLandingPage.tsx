import {
    Grid,
    IpCard,
    JournalCard,
    ProductCard,
    PublicationItem,
    ResearchCard,
    ResearchHero,
    ResearchShell,
    SectionBand,
    ServiceCard,
    ExpertiseGroupCard,
} from '@/Components/Research/ResearchComponents';
import {
    CommunityService,
    ExpertiseGroup,
    IntellectualProperty,
    Journal,
    Product,
    Publication,
    ResearchPageProps,
    ResearchProject,
} from '@/types/research';

export default function ResearchLandingPage({
    locale,
    seo,
    research,
    services,
    publications,
    products,
    ips,
    groups,
    journals,
}: ResearchPageProps & {
    research: ResearchProject[];
    services: CommunityService[];
    publications: Publication[];
    products: Product[];
    ips: IntellectualProperty[];
    groups: ExpertiseGroup[];
    journals: Journal[];
}) {
    const en = locale === 'en';
    return (
        <ResearchShell locale={locale} seo={seo}>
            <ResearchHero locale={locale} />
            <SectionBand title={en ? 'Featured Research' : 'Penelitian Unggulan'}>
                <Grid
                    items={research}
                    empty="No research yet."
                    render={(item) => <ResearchCard key={item.id} item={item} locale={locale} />}
                />
            </SectionBand>
            <SectionBand title={en ? 'Community Service' : 'Pengabdian'}>
                <Grid
                    items={services}
                    empty="No community service yet."
                    render={(item) => <ServiceCard key={item.id} item={item} locale={locale} />}
                />
            </SectionBand>
            <SectionBand title={en ? 'Selected Publications' : 'Publikasi Terpilih'}>
                <div className="grid gap-4">
                    {publications.map((item) => (
                        <PublicationItem key={item.id} item={item} />
                    ))}
                </div>
            </SectionBand>
            <SectionBand title={en ? 'Digital Products' : 'Produk Digital'}>
                <Grid
                    items={products}
                    empty="No products yet."
                    render={(item) => <ProductCard key={item.id} item={item} locale={locale} />}
                />
            </SectionBand>
            <SectionBand title={en ? 'Intellectual Property' : 'HKI'}>
                <div className="grid gap-4 md:grid-cols-2">
                    {ips.map((item) => (
                        <IpCard key={item.id} item={item} />
                    ))}
                </div>
            </SectionBand>
            <SectionBand title={en ? 'Expertise Groups' : 'Kelompok Keahlian'}>
                <Grid
                    items={groups}
                    empty="No active groups yet."
                    render={(item) => <ExpertiseGroupCard key={item.id} item={item} />}
                />
            </SectionBand>
            <SectionBand title={en ? 'Journal Hub' : 'Rumah Jurnal'}>
                <Grid
                    items={journals}
                    empty="No journals yet."
                    render={(item) => <JournalCard key={item.id} item={item} locale={locale} />}
                />
            </SectionBand>
        </ResearchShell>
    );
}
