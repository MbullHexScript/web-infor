import {
    AdminDataTable,
    AdminMetricCard,
    AdminQuickActions,
    AdminSidebar,
    AdminTopbar,
} from '@/Components/Admin/AdminComponents';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

type Props = {
    metrics: Record<string, string | number | null>;
    activity: Record<string, unknown>[];
    latestContent: Record<string, unknown>[];
    upcomingEvents: Record<string, unknown>[];
    newMessages: Record<string, unknown>[];
    needsVerification: Record<string, unknown>[];
    security: Record<string, unknown>;
};

export default function Dashboard({
    metrics,
    activity,
    latestContent,
    upcomingEvents,
    newMessages,
    needsVerification,
    security,
}: Props) {
    return (
        <AuthenticatedLayout
            header={
                <AdminTopbar
                    title="Dashboard Admin"
                    description="Ringkasan operasional website Program Studi Informatika."
                />
            }
        >
            <Head title="Dashboard Admin" />
            <div className="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[240px_1fr]">
                <AdminSidebar />
                <div className="grid gap-6">
                    <AdminQuickActions />
                    <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        {Object.entries(metrics).map(([key, value]) => (
                            <AdminMetricCard key={key} label={key} value={value} />
                        ))}
                    </div>
                    <section className="grid gap-5 xl:grid-cols-2">
                        <Panel title="Aktivitas terbaru">
                            <AdminDataTable
                                rows={activity}
                                columns={['action', 'module', 'description', 'created_at']}
                            />
                        </Panel>
                        <Panel title="Konten terbaru">
                            <AdminDataTable
                                rows={latestContent}
                                columns={['title_id', 'status', 'updated_at']}
                            />
                        </Panel>
                        <Panel title="Agenda terdekat">
                            <AdminDataTable
                                rows={upcomingEvents}
                                columns={['title_id', 'start_at', 'status']}
                            />
                        </Panel>
                        <Panel title="Pesan baru">
                            <AdminDataTable
                                rows={newMessages}
                                columns={['name', 'subject', 'created_at']}
                            />
                        </Panel>
                        <Panel title="Perlu verifikasi">
                            <AdminDataTable
                                rows={needsVerification}
                                columns={['module', 'title', 'status']}
                            />
                        </Panel>
                        <Panel title="Security summary">
                            <div className="grid gap-3 sm:grid-cols-2">
                                {Object.entries(security).map(([key, value]) => (
                                    <AdminMetricCard key={key} label={key} value={String(value)} />
                                ))}
                            </div>
                        </Panel>
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

function Panel({ title, children }: { title: string; children: React.ReactNode }) {
    return (
        <section>
            <h2 className="mb-3 text-lg font-black">{title}</h2>
            {children}
        </section>
    );
}
