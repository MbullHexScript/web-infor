import {
    AdminDataTable,
    AdminMetricCard,
    AdminPage,
    AdminTopbar,
} from '@/Components/Admin/AdminComponents';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';

export default function OperationsStatusPage({
    backups,
    health,
    maintenance,
}: {
    backups: Record<string, unknown>[];
    health: Record<string, unknown>;
    maintenance: boolean;
}) {
    return (
        <AuthenticatedLayout
            header={
                <AdminTopbar
                    title="Operations"
                    description="Backup, health check, dan maintenance mode."
                />
            }
        >
            <Head title="Operations" />
            <AdminPage>
                <div className="grid gap-4 sm:grid-cols-4">
                    {Object.entries(health).map(([key, value]) => (
                        <AdminMetricCard key={key} label={key} value={String(value)} />
                    ))}
                    <AdminMetricCard label="maintenance" value={maintenance ? 'on' : 'off'} />
                </div>
                <div className="flex gap-2">
                    <button
                        onClick={() => router.post(route('admin.backups.run'))}
                        className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                    >
                        Run backup
                    </button>
                    <button
                        onClick={() =>
                            router.post(route('admin.maintenance.toggle'), { enable: !maintenance })
                        }
                        className="rounded-md border border-brand-silver px-4 py-2 text-sm font-bold"
                    >
                        {maintenance ? 'Disable' : 'Enable'} maintenance
                    </button>
                </div>
                <AdminDataTable
                    rows={backups}
                    columns={['id', 'backup_type', 'status', 'path', 'size', 'finished_at']}
                />
            </AdminPage>
        </AuthenticatedLayout>
    );
}
