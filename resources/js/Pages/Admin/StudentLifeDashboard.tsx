import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router, usePage } from '@inertiajs/react';

type Props = {
    mode?: string;
    metrics: Record<string, number>;
    achievements?: Record<string, string | number | boolean | null>[];
    scholarships?: Record<string, string | number | boolean | null>[];
    competitions?: Record<string, string | number | boolean | null>[];
    alumni?: Record<string, string | number | boolean | null>[];
    facilities?: Record<string, string | number | boolean | null>[];
    paths?: Record<string, string | number | boolean | null>[];
    faqs?: Record<string, string | number | boolean | null>[];
    migrations?: Record<string, string | number | boolean | null>[];
};

export default function StudentLifeDashboard(props: Props) {
    const flash = usePage().props.flash as { status?: string } | undefined;

    return (
        <AuthenticatedLayout
            header={
                <div>
                    <h1 className="text-2xl font-black text-brand-deep dark:text-white">
                        {props.mode === 'facilities'
                            ? 'Admin Fasilitas'
                            : props.mode === 'admissions'
                              ? 'Admin PMB'
                              : 'Admin Kemahasiswaan'}
                    </h1>
                    <p className="mt-1 text-sm text-brand-graphite dark:text-slate-300">
                        Dashboard monitoring, verifikasi, dan migrasi konten Tahap 7.
                    </p>
                </div>
            }
        >
            <Head title="Admin Kemahasiswaan" />
            <div className="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6">
                {flash?.status && (
                    <div className="rounded-md border border-green-300 bg-green-50 p-4 text-sm font-bold text-green-800">
                        {flash.status}
                    </div>
                )}
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {Object.entries(props.metrics).map(([key, value]) => (
                        <div
                            key={key}
                            className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]"
                        >
                            <div className="text-3xl font-black text-brand-deep dark:text-brand-lavender">
                                {value}
                            </div>
                            <div className="mt-1 text-xs font-bold uppercase text-brand-graphite dark:text-slate-300">
                                {key}
                            </div>
                        </div>
                    ))}
                </div>
                {!props.mode && (
                    <div className="flex flex-wrap gap-3">
                        <button
                            onClick={() => router.post(route('admin.student-life.legacy.inspect'))}
                            className="rounded-md border border-brand-silver px-4 py-3 text-sm font-bold dark:border-slate-700"
                        >
                            Inspect legacy
                        </button>
                        <button
                            onClick={() =>
                                router.post(route('admin.student-life.legacy.import'), {
                                    type: 'all',
                                    dry_run: true,
                                })
                            }
                            className="rounded-md bg-brand-deep px-4 py-3 text-sm font-bold text-white"
                        >
                            Dry-run import
                        </button>
                    </div>
                )}
                <DataSection title="Prestasi" rows={props.achievements} />
                <DataSection title="Beasiswa" rows={props.scholarships} />
                <DataSection title="Kompetisi" rows={props.competitions} />
                <DataSection title="Alumni" rows={props.alumni} />
                <DataSection title="Fasilitas" rows={props.facilities} />
                <DataSection title="Jalur PMB" rows={props.paths} />
                <DataSection title="FAQ PMB" rows={props.faqs} />
                <DataSection title="Migrasi" rows={props.migrations} />
            </div>
        </AuthenticatedLayout>
    );
}

function DataSection({
    title,
    rows,
}: {
    title: string;
    rows?: Record<string, string | number | boolean | null>[];
}) {
    if (!rows || rows.length === 0) return null;

    return (
        <section className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <h2 className="text-xl font-black">{title}</h2>
            <div className="mt-4 overflow-x-auto">
                <table className="min-w-full text-left text-sm">
                    <thead>
                        <tr>
                            {Object.keys(rows[0])
                                .slice(0, 6)
                                .map((key) => (
                                    <th key={key} className="border-b border-brand-silver p-2">
                                        {key}
                                    </th>
                                ))}
                        </tr>
                    </thead>
                    <tbody>
                        {rows.map((row, index) => (
                            <tr key={index}>
                                {Object.keys(rows[0])
                                    .slice(0, 6)
                                    .map((key) => (
                                        <td key={key} className="border-b border-brand-silver p-2">
                                            {String(row[key] ?? '-')}
                                        </td>
                                    ))}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </section>
    );
}
