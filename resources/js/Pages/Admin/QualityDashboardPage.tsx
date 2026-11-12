import {
    AdminDataTable,
    AdminMetricCard,
    AdminPage,
    AdminQuickActions,
    AdminTopbar,
} from '@/Components/Admin/AdminComponents';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

type Summary = {
    routes?: { total?: number; public?: number; admin?: number };
    verification?: Record<string, number>;
    translations?: { total_issues?: number; navigation_missing?: number };
    media?: { missing_alt?: number; orphan?: number };
    redirects?: { loops?: number; chains?: number; duplicates?: number };
    rps?: { total_courses?: number; total_rps?: number; courses_without_rps?: number };
    seo?: { missing_title?: number; missing_description?: number; duplicate_title?: number };
    security?: Record<string, unknown>;
};

export default function QualityDashboardPage({
    title,
    description,
    summary,
    items = [],
    mode,
}: {
    title: string;
    description: string;
    summary: Summary;
    items: Record<string, unknown>[];
    mode: 'summary' | 'content' | 'rps';
}) {
    const metrics = [
        ['Total route', summary.routes?.total],
        ['Route publik', summary.routes?.public],
        ['Route admin', summary.routes?.admin],
        ['Verified', summary.verification?.verified],
        ['Pending', summary.verification?.pending],
        ['Needs review', summary.verification?.needs_review],
        ['Rejected', summary.verification?.rejected],
        ['Translation issues', summary.translations?.total_issues],
        ['Media tanpa alt', summary.media?.missing_alt],
        ['Media orphan', summary.media?.orphan],
        ['Redirect loop', summary.redirects?.loops],
        ['Redirect chain', summary.redirects?.chains],
        ['Mata kuliah tanpa RPS', summary.rps?.courses_without_rps],
        ['SEO missing title', summary.seo?.missing_title],
        ['SEO missing description', summary.seo?.missing_description],
    ];

    const columns =
        mode === 'rps'
            ? ['code', 'name_id', 'name_en', 'semester', 'credits']
            : [
                  'ID',
                  'Modul',
                  'Jenis konten',
                  'Judul',
                  'Status verifikasi',
                  'Status terjemahan',
                  'Catatan',
              ];

    return (
        <AuthenticatedLayout header={<AdminTopbar title={title} description={description} />}>
            <Head title={title} />
            <AdminPage>
                <div className="flex flex-wrap gap-2">
                    <Link
                        href={route('admin.quality.index')}
                        className="rounded-md border border-brand-silver px-3 py-2 text-sm font-bold"
                    >
                        Summary
                    </Link>
                    <Link
                        href={route('admin.quality.content-verification')}
                        className="rounded-md border border-brand-silver px-3 py-2 text-sm font-bold"
                    >
                        Content verification
                    </Link>
                    <Link
                        href={route('admin.quality.rps-coverage')}
                        className="rounded-md border border-brand-silver px-3 py-2 text-sm font-bold"
                    >
                        RPS coverage
                    </Link>
                </div>

                <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                    {metrics.map(([label, value]) => (
                        <AdminMetricCard key={label} label={String(label)} value={value ?? 0} />
                    ))}
                </div>

                {mode === 'summary' ? <AdminQuickActions /> : null}
                {mode !== 'summary' ? <AdminDataTable rows={items} columns={columns} /> : null}
            </AdminPage>
        </AuthenticatedLayout>
    );
}
