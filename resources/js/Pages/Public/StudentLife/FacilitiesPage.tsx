import {
    FacilityCard,
    FilterBar,
    Grid,
    Pagination,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Facility, Locale, Paginator, Seo } from '@/types/student-life';

export default function FacilitiesPage({
    locale,
    seo,
    items,
    filters,
}: {
    locale: Locale;
    seo: Seo;
    items: Paginator<Facility>;
    filters: Record<string, string>;
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero locale={locale} title={locale === 'en' ? 'Facilities' : 'Fasilitas'} />
            <SectionBand>
                <FilterBar
                    filters={filters}
                    placeholder={locale === 'en' ? 'Category' : 'Kategori'}
                />
                <div className="mt-6">
                    <Grid
                        items={items.data}
                        empty={
                            locale === 'en'
                                ? 'No verified facilities yet.'
                                : 'Fasilitas terverifikasi belum tersedia.'
                        }
                        render={(item) => (
                            <FacilityCard key={item.id} item={item} locale={locale} />
                        )}
                    />
                </div>
                <Pagination links={items.links} />
            </SectionBand>
        </StudentLifeShell>
    );
}
