import {
    AdminFilterBar,
    AdminMetricCard,
    AdminPagination,
    AdminPage,
    AdminTopbar,
    MediaGrid,
    MediaUploadDialog,
    Paginated,
} from '@/Components/Admin/AdminComponents';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';

type MediaItem = Record<string, unknown>;

export default function MediaLibraryPage({
    items,
    filters,
    audit,
}: {
    items: Paginated<MediaItem>;
    filters: Record<string, string>;
    audit: Record<string, number>;
}) {
    const flash = usePage().props.flash as { status?: string };
    return (
        <AuthenticatedLayout
            header={
                <AdminTopbar
                    title="Media Library"
                    description="Upload, metadata, usage, dan audit orphan media."
                />
            }
        >
            <Head title="Media Library" />
            <AdminPage>
                {flash?.status && <Notice text={flash.status} />}
                <div className="grid gap-4 sm:grid-cols-3">
                    {Object.entries(audit)
                        .filter(([, value]) => typeof value === 'number')
                        .map(([key, value]) => (
                            <AdminMetricCard key={key} label={key} value={value} />
                        ))}
                </div>
                <MediaUploadDialog />
                <AdminFilterBar filters={filters} />
                <MediaGrid items={items.data} />
                <AdminPagination links={items.links} />
            </AdminPage>
        </AuthenticatedLayout>
    );
}

function Notice({ text }: { text: string }) {
    return (
        <div className="rounded-md border border-green-300 bg-green-50 p-3 text-sm font-bold text-green-800">
            {text}
        </div>
    );
}
