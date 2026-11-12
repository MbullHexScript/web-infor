import {
    AlumniCard,
    Grid,
    Pagination,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Alumni, Locale, Paginator, Seo } from '@/types/student-life';

export default function AlumniPage({
    locale,
    seo,
    items,
}: {
    locale: Locale;
    seo: Seo;
    items: Paginator<Alumni>;
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={locale === 'en' ? 'Alumni' : 'Alumni'}
                description={
                    locale === 'en'
                        ? 'Alumni stories are shown only with consent.'
                        : 'Cerita alumni hanya ditampilkan dengan persetujuan.'
                }
            />
            <SectionBand>
                <Grid
                    items={items.data}
                    empty={
                        locale === 'en'
                            ? 'No consent-verified alumni stories yet.'
                            : 'Cerita alumni dengan consent belum tersedia.'
                    }
                    render={(item) => <AlumniCard key={item.id} item={item} />}
                />
                <Pagination links={items.links} />
            </SectionBand>
        </StudentLifeShell>
    );
}
