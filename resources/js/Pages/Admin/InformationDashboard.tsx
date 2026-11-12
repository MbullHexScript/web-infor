import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router, usePage } from '@inertiajs/react';

type Row = Record<string, string | number | boolean | null>;

export default function InformationDashboard({
    metrics,
    news,
    announcements,
    events,
    albums,
    documents,
    faqs,
    migrations,
}: {
    metrics: Record<string, number>;
    news: Row[];
    announcements: Row[];
    events: Row[];
    albums: Row[];
    documents: Row[];
    faqs: Row[];
    migrations: Row[];
    routes: Record<string, string>;
}) {
    const status = (usePage().props.flash as { status?: string } | undefined)?.status;
    return (
        <AuthenticatedLayout header={<h1 className="text-xl font-bold">Dashboard Informasi</h1>}>
            <Head title="Dashboard Informasi" />
            <div className="mx-auto grid max-w-7xl gap-6 p-6">
                {status && (
                    <div className="rounded-md bg-green-50 p-4 text-sm font-bold text-green-700">
                        {status}
                    </div>
                )}
                <div className="grid gap-4 md:grid-cols-3 xl:grid-cols-4">
                    {Object.entries(metrics).map(([key, value]) => (
                        <div
                            key={key}
                            className="rounded-md bg-white p-5 shadow-sm dark:bg-[#1c2128]"
                        >
                            <div className="text-sm text-gray-500">{key}</div>
                            <div className="text-3xl font-black">{value}</div>
                        </div>
                    ))}
                </div>
                <section className="rounded-md bg-white p-6 shadow-sm dark:bg-[#1c2128]">
                    <h2 className="font-bold">Migrasi Konten Lama</h2>
                    <div className="mt-4 flex flex-wrap gap-3">
                        <button
                            onClick={() => router.post(route('admin.information.legacy.inspect'))}
                            className="rounded-md border px-4 py-2 text-sm font-bold"
                        >
                            Inspect Legacy
                        </button>
                        <button
                            onClick={() => router.post(route('admin.information.legacy.import'))}
                            className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                        >
                            Import Pending
                        </button>
                    </div>
                    <SimpleList
                        rows={migrations}
                        titleKey="source_url"
                        metaKey="verification_status"
                    />
                </section>
                <DashboardSection title="Berita" rows={news} titleKey="title_id" metaKey="status" />
                <DashboardSection
                    title="Pengumuman"
                    rows={announcements}
                    titleKey="title_id"
                    metaKey="priority"
                />
                <DashboardSection
                    title="Agenda"
                    rows={events}
                    titleKey="title_id"
                    metaKey="start_at"
                />
                <DashboardSection
                    title="Galeri"
                    rows={albums}
                    titleKey="title_id"
                    metaKey="verification_status"
                />
                <DashboardSection
                    title="Dokumen"
                    rows={documents}
                    titleKey="title_id"
                    metaKey="document_status"
                />
                <DashboardSection
                    title="FAQ"
                    rows={faqs}
                    titleKey="question_id"
                    metaKey="category"
                />
            </div>
        </AuthenticatedLayout>
    );
}

function DashboardSection({
    title,
    rows,
    titleKey,
    metaKey,
}: {
    title: string;
    rows: Row[];
    titleKey: string;
    metaKey: string;
}) {
    return (
        <section className="rounded-md bg-white p-6 shadow-sm dark:bg-[#1c2128]">
            <h2 className="font-bold">{title}</h2>
            <SimpleList rows={rows} titleKey={titleKey} metaKey={metaKey} />
        </section>
    );
}

function SimpleList({
    rows,
    titleKey,
    metaKey,
}: {
    rows: Row[];
    titleKey: string;
    metaKey: string;
}) {
    return (
        <div className="mt-3 grid gap-2">
            {rows.length === 0 ? <p className="text-sm text-gray-500">Belum ada data.</p> : null}
            {rows.map((row, index) => (
                <div
                    key={`${row.id ?? index}`}
                    className="rounded-md border p-3 text-sm dark:border-slate-700"
                >
                    <span className="font-bold">{String(row[titleKey] ?? '-')}</span>
                    <span className="ml-2 text-gray-500">{String(row[metaKey] ?? '-')}</span>
                </div>
            ))}
        </div>
    );
}
