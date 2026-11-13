import { AcademicShell, PdfPreview, RpsMetadata } from '@/Components/Academic/AcademicComponents';
import { AcademicPageProps } from '@/types/academics';

export default function RpsPreview({
    locale,
    seo,
    rps,
}: AcademicPageProps & { rps: Record<string, unknown> }) {
    return (
        <AcademicShell
            locale={locale}
            title={seo?.title ?? 'Preview RPS'}
            description={seo?.description ?? ''}
        >
            <div className="public-section mx-auto grid max-w-7xl gap-5 px-4 py-10 sm:px-6 lg:grid-cols-[1fr_360px]">
                <PdfPreview src={`/storage/${rps.file_id}`} />
                <RpsMetadata rps={rps} />
            </div>
        </AcademicShell>
    );
}
