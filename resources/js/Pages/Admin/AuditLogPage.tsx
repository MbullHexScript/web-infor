import {
    AdminDataTable,
    AdminFilterBar,
    AdminPage,
    AdminPagination,
    AdminTopbar,
} from '@/Components/Admin/AdminComponents';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function AuditLogPage({
    items,
    filters,
}: {
    items: {
        data: Record<string, unknown>[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    filters: Record<string, string>;
}) {
    return (
        <AuthenticatedLayout
            header={
                <AdminTopbar
                    title="Audit Log"
                    description="Log admin tidak dapat diedit melalui UI."
                />
            }
        >
            <Head title="Audit Log" />
            <AdminPage>
                <AdminFilterBar filters={filters} />
                <AdminDataTable
                    rows={items.data}
                    columns={[
                        'action',
                        'module',
                        'record_type',
                        'description',
                        'ip_address',
                        'created_at',
                    ]}
                />
                <AdminPagination links={items.links} />
            </AdminPage>
        </AuthenticatedLayout>
    );
}
