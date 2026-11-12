import { AdminDataTable, AdminPage, AdminTopbar } from '@/Components/Admin/AdminComponents';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

export default function AccountSecurityPage({
    user,
    sessions,
    recoveryCodes,
}: {
    user: Record<string, string | null>;
    sessions: Record<string, unknown>[];
    recoveryCodes: string[];
}) {
    const form = useForm({
        name: user.name ?? '',
        email: user.email ?? '',
        password: '',
        password_confirmation: '',
    });
    function submit(event: FormEvent) {
        event.preventDefault();
        form.patch(route('admin.account.update'), { preserveScroll: true });
    }
    return (
        <AuthenticatedLayout
            header={
                <AdminTopbar
                    title="Account Security"
                    description="Profil admin, password, 2FA, recovery codes, dan sesi aktif."
                />
            }
        >
            <Head title="Account Security" />
            <AdminPage>
                <form
                    onSubmit={submit}
                    className="grid gap-3 rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]"
                >
                    <input
                        value={form.data.name}
                        onChange={(e) => form.setData('name', e.target.value)}
                        className="rounded-md border-brand-silver"
                    />
                    <input
                        value={form.data.email}
                        onChange={(e) => form.setData('email', e.target.value)}
                        className="rounded-md border-brand-silver"
                    />
                    <input
                        type="password"
                        value={form.data.password}
                        onChange={(e) => form.setData('password', e.target.value)}
                        placeholder="New password"
                        className="rounded-md border-brand-silver"
                    />
                    <input
                        type="password"
                        value={form.data.password_confirmation}
                        onChange={(e) => form.setData('password_confirmation', e.target.value)}
                        placeholder="Confirm password"
                        className="rounded-md border-brand-silver"
                    />
                    <button className="w-fit rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white">
                        Save
                    </button>
                </form>
                <button
                    onClick={() => router.post(route('admin.account.recovery-codes'))}
                    className="w-fit rounded-md border border-brand-silver px-4 py-2 text-sm font-bold"
                >
                    Regenerate recovery codes
                </button>
                <div className="grid gap-2 rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]">
                    {recoveryCodes.map((code) => (
                        <code key={code}>{code}</code>
                    ))}
                </div>
                <h2 className="text-lg font-black">Active sessions</h2>
                <AdminDataTable
                    rows={sessions}
                    columns={['ip_address', 'user_agent', 'last_activity']}
                />
            </AdminPage>
        </AuthenticatedLayout>
    );
}
