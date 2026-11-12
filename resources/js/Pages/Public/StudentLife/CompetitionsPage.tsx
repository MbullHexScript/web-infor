import {
    CompetitionCard,
    FilterBar,
    Grid,
    Pagination,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Competition, Locale, Paginator, Seo } from '@/types/student-life';

export default function CompetitionsPage({
    locale,
    seo,
    items,
    filters,
}: {
    locale: Locale;
    seo: Seo;
    items: Paginator<Competition>;
    filters: Record<string, string>;
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={locale === 'en' ? 'Competitions' : 'Kompetisi'}
            />
            <SectionBand>
                <FilterBar
                    filters={filters}
                    placeholder={locale === 'en' ? 'Search competitions' : 'Cari kompetisi'}
                />
                <div className="mt-6">
                    <Grid
                        items={items.data}
                        empty={
                            locale === 'en'
                                ? 'No verified competitions yet.'
                                : 'Kompetisi terverifikasi belum tersedia.'
                        }
                        render={(item) => (
                            <CompetitionCard key={item.id} item={item} locale={locale} />
                        )}
                    />
                </div>
                <Pagination links={items.links} />
            </SectionBand>
        </StudentLifeShell>
    );
}
