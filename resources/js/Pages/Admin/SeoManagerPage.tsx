import {
    AdminDataTable,
    AdminMetricCard,
    AdminPage,
    AdminTopbar,
} from '@/Components/Admin/AdminComponents';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';

export default function SeoManagerPage({
    settings,
    metadata,
    report,
}: {
    settings: Record<string, unknown> | null;
    metadata: { data: Record<string, unknown>[] };
    report: Record<string, number>;
}) {
    return (
        <AuthenticatedLayout
            header={
                <AdminTopbar
                    title="SEO Manager"
                    description="Global SEO, sitemap, dan audit metadata."
                />
            }
        >
            <Head title="SEO Manager" />
            <AdminPage>
                <div className="grid gap-4 sm:grid-cols-4">
                    {Object.entries(report).map(([key, value]) => (
                        <AdminMetricCard key={key} label={key} value={value} />
                    ))}
                </div>
                <section className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
                    <h2 className="text-lg font-black">Global settings</h2>
                    <p className="mt-2 text-sm">Title: {String(settings?.site_title_id ?? '-')}</p>
                    <button
                        onClick={() => router.post(route('admin.seo.sitemap.generate'))}
                        className="mt-4 rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                    >
                        Generate sitemap
                    </button>
                </section>
                <AdminDataTable
                    rows={metadata.data}
                    columns={[
                        'seoable_type',
                        'seoable_id',
                        'meta_title_id',
                        'canonical_url',
                        'updated_at',
                    ]}
                />
            </AdminPage>
        </AuthenticatedLayout>
    );
}
