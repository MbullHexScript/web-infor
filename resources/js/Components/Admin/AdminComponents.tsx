import { Link, router, useForm } from '@inertiajs/react';
import {
    Activity,
    Archive,
    BarChart3,
    Database,
    FileImage,
    FileText,
    HeartPulse,
    Link2,
    Menu,
    Search,
    Settings,
    ShieldCheck,
    Upload,
} from 'lucide-react';
import { FormEvent, ReactNode, useState } from 'react';

export type Paginated<T> = {
    data: T[];
    links: { url: string | null; label: string; active: boolean }[];
};

export function AdminMetricCard({
    label,
    value,
}: {
    label: string;
    value: string | number | null;
}) {
    return (
        <div className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="text-3xl font-black text-brand-deep dark:text-brand-lavender">
                {value ?? '-'}
            </div>
            <div className="mt-1 text-xs font-bold uppercase text-brand-graphite dark:text-slate-300">
                {label}
            </div>
        </div>
    );
}

export function AdminQuickActions() {
    const actions = [
        ['Tambah berita', route('admin.news.index')],
        ['Tambah pengumuman', route('admin.announcements.index')],
        ['Tambah agenda', route('admin.events.index')],
        ['Upload RPS', route('admin.rps.index')],
        ['Upload media', route('admin.media.index')],
        ['Edit homepage', route('admin.homepage.edit')],
        ['Pesan masuk', route('admin.contact.index')],
        ['Preview website', '/id'],
    ];
    return (
        <div className="flex flex-wrap gap-2">
            {actions.map(([label, href]) => (
                <Link
                    key={label}
                    href={href}
                    className="rounded-md border border-brand-silver bg-white px-3 py-2 text-sm font-bold dark:border-slate-700 dark:bg-[#1c2128]"
                >
                    {label}
                </Link>
            ))}
        </div>
    );
}

export function AdminSidebar() {
    const [open, setOpen] = useState(false);
    const items = [
        ['Dashboard', route('admin.dashboard'), BarChart3],
        ['Beranda', route('admin.homepage.edit'), Settings],
        ['Profil', route('admin.profile-module.dashboard'), FileText],
        ['Akademik', route('admin.academic.dashboard'), Database],
        ['Informasi', route('admin.information.dashboard'), FileText],
        ['Riset', route('admin.research.dashboard'), Activity],
        ['Kemahasiswaan', route('admin.student-life.dashboard'), Activity],
        ['PMB', route('admin.admissions.index'), FileText],
        ['Fasilitas', route('admin.facilities.index'), Database],
        ['Kontak', route('admin.contact.index'), Activity],
        ['Media Library', route('admin.media.index'), FileImage],
        ['SEO', route('admin.seo.index'), Search],
        ['Redirect', route('admin.redirects.index'), Link2],
        ['Pengaturan', route('admin.settings.index'), Settings],
        ['Audit Log', route('admin.audit-log.index'), Archive],
        ['Operasional', route('admin.operations.index'), HeartPulse],
        ['Quality', route('admin.quality.index'), ShieldCheck],
        ['Akun', route('admin.account.index'), ShieldCheck],
    ] as const;
    return (
        <>
            <button
                className="mb-3 rounded-md border border-brand-silver p-2 lg:hidden"
                onClick={() => setOpen(!open)}
                aria-label="Toggle admin menu"
            >
                <Menu size={18} />
            </button>
            <aside className={`${open ? 'block' : 'hidden'} lg:block`}>
                <nav className="grid gap-1 rounded-md border border-brand-silver bg-white p-2 dark:border-slate-700 dark:bg-[#1c2128]">
                    {items.map(([label, href, Icon]) => (
                        <Link
                            key={label}
                            href={href}
                            className="hover:bg-brand-lavender/20 flex items-center gap-2 rounded-md px-3 py-2 text-sm font-bold"
                        >
                            <Icon size={16} /> {label}
                        </Link>
                    ))}
                </nav>
            </aside>
        </>
    );
}

export function AdminTopbar({ title, description }: { title: string; description?: string }) {
    return (
        <div>
            <h1 className="text-2xl font-black text-brand-deep dark:text-white">{title}</h1>
            {description && (
                <p className="mt-1 text-sm text-brand-graphite dark:text-slate-300">
                    {description}
                </p>
            )}
        </div>
    );
}

export function AdminPage({ children }: { children: ReactNode }) {
    return (
        <div className="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[240px_1fr]">
            <AdminSidebar />
            <div className="grid gap-5">{children}</div>
        </div>
    );
}

export function AdminDataTable<T extends Record<string, unknown>>({
    rows,
    columns,
    actions,
}: {
    rows: T[];
    columns: string[];
    actions?: (row: T) => ReactNode;
}) {
    if (rows.length === 0) return <AdminEmptyState title="Belum ada data" />;
    return (
        <div className="overflow-x-auto rounded-md border border-brand-silver bg-white dark:border-slate-700 dark:bg-[#1c2128]">
            <table className="min-w-full text-left text-sm">
                <thead>
                    <tr>
                        {columns.map((column) => (
                            <th key={column} className="border-b border-brand-silver p-3">
                                {column}
                            </th>
                        ))}
                        {actions && <th className="border-b border-brand-silver p-3">Aksi</th>}
                    </tr>
                </thead>
                <tbody>
                    {rows.map((row, index) => (
                        <tr key={index}>
                            {columns.map((column) => (
                                <td key={column} className="border-b border-brand-silver p-3">
                                    {String(row[column] ?? '-')}
                                </td>
                            ))}
                            {actions && (
                                <td className="border-b border-brand-silver p-3">{actions(row)}</td>
                            )}
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}

export function AdminFilterBar({ filters }: { filters: Record<string, string | undefined> }) {
    const [q, setQ] = useState(filters.q ?? '');
    function submit(event: FormEvent) {
        event.preventDefault();
        router.get(
            window.location.pathname,
            { ...filters, q: q || undefined },
            { preserveState: true },
        );
    }
    return (
        <form onSubmit={submit} className="flex gap-2">
            <input
                value={q}
                onChange={(event) => setQ(event.target.value)}
                className="min-w-0 flex-1 rounded-md border-brand-silver"
                placeholder="Search"
            />
            <button className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white">
                Filter
            </button>
        </form>
    );
}

export function AdminPagination({
    links,
}: {
    links?: { url: string | null; label: string; active: boolean }[];
}) {
    if (!links || links.length <= 3) return null;
    return (
        <nav className="mt-5 flex flex-wrap gap-2">
            {links.map((link, index) => (
                <Link
                    key={index}
                    href={link.url ?? '#'}
                    className={`rounded-md border px-3 py-2 text-sm ${link.active ? 'bg-brand-lavender/20 border-brand-purple font-bold' : 'border-brand-silver'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`}
                    dangerouslySetInnerHTML={{ __html: link.label }}
                />
            ))}
        </nav>
    );
}

export function AdminEmptyState({ title }: { title: string }) {
    return (
        <div className="rounded-md border border-brand-silver bg-white p-5 text-sm dark:border-slate-700 dark:bg-[#1c2128]">
            {title}
        </div>
    );
}

export function MediaUploadDialog() {
    const form = useForm<{ file: File | null; type: string }>({ file: null, type: 'document' });
    function submit(event: FormEvent) {
        event.preventDefault();
        form.post(route('admin.media.store'), { forceFormData: true, preserveScroll: true });
    }
    return (
        <form
            onSubmit={submit}
            className="grid gap-3 rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]"
        >
            <label className="text-sm font-bold">
                Upload media
                <input
                    type="file"
                    className="mt-2 block w-full text-sm"
                    onChange={(event) => form.setData('file', event.target.files?.[0] ?? null)}
                />
            </label>
            {form.errors.file && (
                <div className="text-sm font-bold text-red-600">{String(form.errors.file)}</div>
            )}
            <button className="inline-flex w-fit items-center gap-2 rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white">
                <Upload size={16} /> Upload
            </button>
        </form>
    );
}

export function MediaCard({ item }: { item: Record<string, unknown> }) {
    const isImage = String(item.mimeType).startsWith('image/');
    return (
        <article className="rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]">
            {isImage && item.url ? (
                <img
                    src={String(item.url)}
                    alt={String(item.altText ?? item.title ?? '')}
                    className="h-36 w-full rounded-md object-cover"
                    loading="lazy"
                />
            ) : (
                <div className="flex h-36 items-center justify-center rounded-md bg-brand-soft dark:bg-[#11151a]">
                    <FileText />
                </div>
            )}
            <h3 className="mt-3 line-clamp-2 text-sm font-black">{String(item.title ?? '-')}</h3>
            <p className="mt-1 text-xs text-brand-graphite dark:text-slate-300">
                {String(item.mimeType ?? '-')}
            </p>
            {Boolean(item.url) && (
                <button
                    onClick={() => navigator.clipboard?.writeText(String(item.url))}
                    className="mt-3 rounded-md border border-brand-silver px-3 py-2 text-xs font-bold dark:border-slate-700"
                >
                    Copy URL
                </button>
            )}
        </article>
    );
}

export const AdminActivityFeed = AdminDataTable;
export const AdminSecuritySummary = AdminMetricCard;
export const AdminTranslationSummary = AdminMetricCard;
export const MediaGrid = ({ items }: { items: Record<string, unknown>[] }) => (
    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {items.map((item) => (
            <MediaCard key={String(item.id)} item={item} />
        ))}
    </div>
);
export const MediaList = AdminDataTable;
export const MediaDetailsPanel = AdminEmptyState;
export const MediaPicker = MediaUploadDialog;
export const MediaUsageList = AdminDataTable;
export const ImageCropDialog = AdminEmptyState;
export const FocalPointPicker = AdminEmptyState;
export const PdfMetadataPanel = AdminEmptyState;
export const SeoAuditTable = AdminDataTable;
export const SeoMetadataForm = AdminEmptyState;
export const RedirectForm = AdminEmptyState;
export const RedirectAuditPanel = AdminMetricCard;
export const BrandingSettings = AdminEmptyState;
export const ContactSettings = AdminEmptyState;
export const NavigationManager = AdminDataTable;
export const SocialLinksManager = AdminDataTable;
export const SecuritySettings = AdminEmptyState;
export const MaintenanceSettings = AdminEmptyState;
export const AuditLogDetail = AdminEmptyState;
export const TwoFactorSetup = AdminEmptyState;
export const RecoveryCodesPanel = AdminEmptyState;
export const ActiveSessions = AdminDataTable;
export const BackupStatusPanel = AdminMetricCard;
export const HealthStatusPanel = AdminMetricCard;
export const AdminBulkActions = AdminEmptyState;
export const AdminRowActions = AdminEmptyState;
export const AdminTableSkeleton = AdminEmptyState;
export const AdminFormLayout = ({ children }: { children: ReactNode }) => (
    <div className="grid gap-4">{children}</div>
);
export const TranslationTabs = AdminEmptyState;
export const FormSection = AdminFormLayout;
export const FormField = AdminEmptyState;
export const ValidationSummary = AdminEmptyState;
export const PublishPanel = AdminEmptyState;
export const UnsavedChangesGuard = AdminEmptyState;
export const StickyFormActions = AdminEmptyState;
