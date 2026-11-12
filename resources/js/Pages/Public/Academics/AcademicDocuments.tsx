import {
    AcademicDocumentCard,
    AcademicHero,
    AcademicShell,
    EmptyState,
} from '@/Components/Academic/AcademicComponents';
import { AcademicPageProps } from '@/types/academics';

export default function AcademicDocuments({
    locale,
    seo,
    documents,
}: AcademicPageProps & { documents: Record<string, string>[] }) {
    return (
        <AcademicShell
            locale={locale}
            title={seo?.title ?? 'Dokumen Akademik'}
            description={seo?.description ?? ''}
        >
            <AcademicHero
                locale={locale}
                eyebrow="Akademik"
                title={locale === 'id' ? 'Dokumen Akademik' : 'Academic Documents'}
                description={
                    locale === 'id'
                        ? 'Repositori dokumen akademik publik.'
                        : 'Public academic document repository.'
                }
            />
            <div className="mx-auto grid max-w-7xl gap-4 px-4 py-10 sm:px-6">
                {documents.length ? (
                    documents.map((item) => <AcademicDocumentCard key={item.id} item={item} />)
                ) : (
                    <EmptyState title="Belum ada dokumen akademik publik." />
                )}
            </div>
        </AcademicShell>
    );
}
