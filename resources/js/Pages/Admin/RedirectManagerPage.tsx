import {
    AdminDataTable,
    AdminFilterBar,
    AdminMetricCard,
    AdminPage,
    AdminPagination,
    AdminTopbar,
} from '@/Components/Admin/AdminComponents';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

export default function RedirectManagerPage({
    items,
    filters,
    audit,
}: {
    items: {
        data: Record<string, unknown>[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    filters: Record<string, string>;
    audit: Record<string, unknown>;
}) {
    const form = useForm({ old_path: '', new_path: '', redirect_code: 301 });
    function submit(event: FormEvent) {
        event.preventDefault();
        form.post(route('admin.redirects.store'), { preserveScroll: true });
    }
    return (
        <AuthenticatedLayout
            header={
                <AdminTopbar
                    title="Redirect Manager"
                    description="Kelola redirect legacy dan audit loop/duplicate."
                />
            }
        >
            <Head title="Redirect Manager" />
            <AdminPage>
                <div className="grid gap-4 sm:grid-cols-3">
                    <AdminMetricCard label="total" value={String(audit.total ?? 0)} />
                    <AdminMetricCard label="warnings" value={String(audit.warnings ?? 0)} />
                    <AdminMetricCard
                        label="duplicates"
                        value={Array.isArray(audit.duplicates) ? audit.duplicates.length : 0}
                    />
                </div>
                <form
                    onSubmit={submit}
                    className="grid gap-3 rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128] sm:grid-cols-[1fr_1fr_120px_auto]"
                >
                    <input
                        value={form.data.old_path}
                        onChange={(e) => form.setData('old_path', e.target.value)}
                        placeholder="/old"
                        className="rounded-md border-brand-silver"
                    />
                    <input
                        value={form.data.new_path}
                        onChange={(e) => form.setData('new_path', e.target.value)}
                        placeholder="/new"
                        className="rounded-md border-brand-silver"
                    />
                    <select
                        value={form.data.redirect_code}
                        onChange={(e) => form.setData('redirect_code', Number(e.target.value))}
                        className="rounded-md border-brand-silver"
                    >
                        <option>301</option>
                        <option>302</option>
                        <option>307</option>
                        <option>308</option>
                    </select>
                    <button className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white">
                        Save
                    </button>
                </form>
                <AdminFilterBar filters={filters} />
                <AdminDataTable
                    rows={items.data}
                    columns={['old_path', 'new_path', 'redirect_code', 'hit_count', 'last_hit_at']}
                    actions={(row) => (
                        <button
                            onClick={() =>
                                router.delete(route('admin.redirects.delete', row.id as number))
                            }
                            className="text-sm font-bold text-red-700"
                        >
                            Delete
                        </button>
                    )}
                />
                <AdminPagination links={items.links} />
            </AdminPage>
        </AuthenticatedLayout>
    );
}
