import {
    FilterBar,
    Grid,
    Pagination,
    ScholarshipCard,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Locale, Paginator, Scholarship, Seo } from '@/types/student-life';

export default function ScholarshipsPage({
    locale,
    seo,
    items,
    filters,
}: {
    locale: Locale;
    seo: Seo;
    items: Paginator<Scholarship>;
    filters: Record<string, string>;
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={locale === 'en' ? 'Scholarships' : 'Beasiswa'}
            />
            <SectionBand>
                <FilterBar
                    filters={filters}
                    placeholder={locale === 'en' ? 'Search scholarships' : 'Cari beasiswa'}
                />
                <div className="mt-6">
                    <Grid
                        items={items.data}
                        empty={
                            locale === 'en'
                                ? 'No verified scholarships yet.'
                                : 'Beasiswa terverifikasi belum tersedia.'
                        }
                        render={(item) => (
                            <ScholarshipCard key={item.id} item={item} locale={locale} />
                        )}
                    />
                </div>
                <Pagination links={items.links} />
            </SectionBand>
        </StudentLifeShell>
    );
}
