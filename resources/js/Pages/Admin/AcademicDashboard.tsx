import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function AcademicDashboard({
    curriculum,
    metrics,
    imports,
}: {
    curriculum: Record<string, string | number> | null;
    metrics: Record<string, number>;
    imports: { id: number; status: string; mode: string; rows_processed: number }[];
}) {
    return (
        <AuthenticatedLayout header={<h1 className="text-xl font-bold">Dashboard Akademik</h1>}>
            <Head title="Dashboard Akademik" />
            <div className="mx-auto grid max-w-7xl gap-6 p-6">
                <div className="rounded-md bg-white p-6 shadow-sm dark:bg-[#1c2128]">
                    <div className="text-sm text-gray-500">Kurikulum aktif</div>
                    <div className="mt-1 text-2xl font-black">
                        {curriculum?.name_id ?? 'Belum tersedia'}
                    </div>
                    <div className="mt-4 flex gap-3">
                        <Link
                            href={route('admin.academic.imports')}
                            className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                        >
                            Import Kurikulum
                        </Link>
                        <Link
                            href={route('admin.rps.index')}
                            className="rounded-md border px-4 py-2 text-sm font-bold"
                        >
                            Kelola RPS
                        </Link>
                    </div>
                </div>
                <div className="grid gap-4 md:grid-cols-3">
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
                <div className="rounded-md bg-white p-6 shadow-sm dark:bg-[#1c2128]">
                    <h2 className="font-bold">Import terakhir</h2>
                    <div className="mt-3 grid gap-2">
                        {imports.map((item) => (
                            <div key={item.id} className="rounded-md border p-3 text-sm">
                                #{item.id} {item.mode} - {item.status} ({item.rows_processed} rows)
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
