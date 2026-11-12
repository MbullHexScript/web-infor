import { AdminDataTable, AdminPage, AdminTopbar } from '@/Components/Admin/AdminComponents';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

export default function SiteSettingsPage({
    settings,
    navigation,
    socialLinks,
}: {
    settings: Record<string, unknown>[];
    navigation: Record<string, unknown>[];
    socialLinks: Record<string, unknown>[];
}) {
    const form = useForm({
        group: 'general',
        key: '',
        value: {} as Record<string, string>,
        is_public: false,
    });
    function submit(event: FormEvent) {
        event.preventDefault();
        form.patch(route('admin.settings.update'), { preserveScroll: true });
    }
    return (
        <AuthenticatedLayout
            header={
                <AdminTopbar
                    title="Site Settings"
                    description="Identitas, navigasi, sosial, SEO, upload, security, dan maintenance."
                />
            }
        >
            <Head title="Site Settings" />
            <AdminPage>
                <form
                    onSubmit={submit}
                    className="grid gap-3 rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128] sm:grid-cols-[160px_1fr_auto]"
                >
                    <input
                        value={form.data.group}
                        onChange={(e) => form.setData('group', e.target.value)}
                        className="rounded-md border-brand-silver"
                    />
                    <input
                        value={form.data.key}
                        onChange={(e) => form.setData('key', e.target.value)}
                        placeholder="setting.key"
                        className="rounded-md border-brand-silver"
                    />
                    <button className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white">
                        Save
                    </button>
                </form>
                <h2 className="text-lg font-black">Settings</h2>
                <AdminDataTable rows={settings} columns={['group', 'key', 'type', 'is_public']} />
                <h2 className="text-lg font-black">Navigation</h2>
                <AdminDataTable
                    rows={navigation}
                    columns={['group', 'label_id', 'url', 'is_active', 'sort_order']}
                />
                <h2 className="text-lg font-black">Social links</h2>
                <AdminDataTable
                    rows={socialLinks}
                    columns={['title_id', 'status', 'verification_status']}
                />
            </AdminPage>
        </AuthenticatedLayout>
    );
}
