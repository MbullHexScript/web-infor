import {
    CampusLifeSection,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Locale, Seo } from '@/types/student-life';

export default function CampusLifePage({
    locale,
    seo,
    page,
}: {
    locale: Locale;
    seo: Seo;
    page: { title: string; content: string } | null;
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={page?.title ?? (locale === 'en' ? 'Campus Life' : 'Kehidupan Mahasiswa')}
                description={page?.content}
            />
            <CampusLifeSection locale={locale} content={page?.content} />
            <SectionBand>
                <p className="text-sm text-brand-graphite dark:text-slate-300">
                    {locale === 'en'
                        ? 'Related albums, news, agenda, and student achievements will appear after verified content is available.'
                        : 'Album, berita, agenda, dan prestasi terkait akan tampil setelah konten terverifikasi tersedia.'}
                </p>
            </SectionBand>
        </StudentLifeShell>
    );
}
