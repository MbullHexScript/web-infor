import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router, usePage } from '@inertiajs/react';

type Row = Record<string, string | number | boolean | null>;

export default function ResearchDashboard({
    metrics,
    research,
    services,
    publications,
    products,
    journals,
    migrations,
}: {
    metrics: Record<string, number>;
    research: Row[];
    services: Row[];
    publications: Row[];
    products: Row[];
    journals: Row[];
    migrations: Row[];
}) {
    const status = (usePage().props.flash as { status?: string } | undefined)?.status;
    return (
        <AuthenticatedLayout
            header={<h1 className="text-xl font-bold">Dashboard Riset & Inovasi</h1>}
        >
            <Head title="Dashboard Riset & Inovasi" />
            <div className="mx-auto grid max-w-7xl gap-6 p-6">
                {status && (
                    <div className="rounded-md bg-green-50 p-4 text-sm font-bold text-green-700">
                        {status}
                    </div>
                )}
                <div className="grid gap-4 md:grid-cols-3 xl:grid-cols-5">
                    {Object.entries(metrics).map(([key, value]) => (
                        <Metric key={key} label={key} value={value} />
                    ))}
                </div>
                <section className="rounded-md bg-white p-6 shadow-sm dark:bg-[#1c2128]">
                    <h2 className="font-bold">Migrasi Riset Legacy</h2>
                    <div className="mt-4 flex gap-3">
                        <button
                            onClick={() => router.post(route('admin.research.legacy.inspect'))}
                            className="rounded-md border px-4 py-2 text-sm font-bold"
                        >
                            Inspect
                        </button>
                        <button
                            onClick={() => router.post(route('admin.research.legacy.import'))}
                            className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                        >
                            Import Pending
                        </button>
                    </div>
                    <List rows={migrations} titleKey="source_url" metaKey="verification_status" />
                </section>
                <Panel title="Penelitian" rows={research} titleKey="title_id" metaKey="status" />
                <Panel title="Pengabdian" rows={services} titleKey="title_id" metaKey="status" />
                <Panel
                    title="Publikasi"
                    rows={publications}
                    titleKey="title"
                    metaKey="publication_type"
                />
                <Panel
                    title="Produk Digital"
                    rows={products}
                    titleKey="title_id"
                    metaKey="status"
                />
                <Panel title="Rumah Jurnal" rows={journals} titleKey="name" metaKey="status" />
            </div>
        </AuthenticatedLayout>
    );
}

function Metric({ label, value }: { label: string; value: number }) {
    return (
        <div className="rounded-md bg-white p-5 shadow-sm dark:bg-[#1c2128]">
            <div className="text-sm text-gray-500">{label}</div>
            <div className="text-3xl font-black">{value}</div>
        </div>
    );
}

function Panel({
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
            <List rows={rows} titleKey={titleKey} metaKey={metaKey} />
        </section>
    );
}

function List({ rows, titleKey, metaKey }: { rows: Row[]; titleKey: string; metaKey: string }) {
    return (
        <div className="mt-3 grid gap-2">
            {rows.length === 0 && <p className="text-sm text-gray-500">Belum ada data.</p>}
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
