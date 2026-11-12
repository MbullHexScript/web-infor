import Checkbox from '@/Components/Checkbox';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler, ReactNode } from 'react';

type AdminSection = {
    key: string;
    title_id: string;
    title_en: string | null;
    subtitle_id: string | null;
    subtitle_en: string | null;
    background_style: string;
    is_active: boolean;
    sort_order: number;
};

type AdminQuickLink = {
    key: string;
    label_id: string;
    label_en: string;
    url: string;
    icon: string | null;
    is_external: boolean;
    is_active: boolean;
    sort_order: number;
};

type AdminStatistic = {
    key: string;
    label_id: string;
    label_en: string | null;
    value: string | null;
    suffix: string | null;
    verification_status: string;
    is_active: boolean;
    sort_order: number;
};

type HomepageAdminProps = {
    sections: AdminSection[];
    quickLinks: AdminQuickLink[];
    statistics: AdminStatistic[];
};

export default function Homepage({ sections, quickLinks, statistics }: HomepageAdminProps) {
    return (
        <AuthenticatedLayout header={<h1 className="font-heading text-2xl font-bold">Homepage</h1>}>
            <Head title="Admin Homepage" />
            <div className="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6">
                <div className="flex flex-wrap items-center justify-between gap-3 rounded-[var(--radius-md)] border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
                    <div>
                        <h2 className="text-lg font-bold">Pengaturan Homepage Publik</h2>
                        <p className="mt-1 text-sm text-brand-graphite dark:text-slate-300">
                            Kelola status section, urutan, quick links, dan statistik terverifikasi.
                        </p>
                    </div>
                    <Link
                        href="/id"
                        className="inline-flex min-h-11 items-center rounded-[var(--radius-sm)] bg-brand-deep px-5 py-3 text-sm font-bold text-white"
                    >
                        Preview Homepage
                    </Link>
                </div>
                <SectionsForm sections={sections} />
                <QuickLinksForm quickLinks={quickLinks} />
                <StatisticsForm statistics={statistics} />
            </div>
        </AuthenticatedLayout>
    );
}

function SectionsForm({ sections }: { sections: AdminSection[] }) {
    const form = useForm({ sections });

    const submit: FormEventHandler = (event) => {
        event.preventDefault();
        form.patch(route('admin.homepage.sections.update'), { preserveScroll: true });
    };

    return (
        <form
            onSubmit={submit}
            className="rounded-[var(--radius-md)] border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]"
        >
            <h2 className="text-lg font-bold">Homepage Sections</h2>
            <div className="mt-5 grid gap-4">
                {form.data.sections.map((section, index) => (
                    <div
                        key={section.key}
                        className="grid gap-3 rounded-md border border-brand-silver p-4 dark:border-slate-700 lg:grid-cols-[4rem_1fr_1fr_7rem_5rem]"
                    >
                        <TextInput
                            type="number"
                            value={section.sort_order}
                            onChange={(event) =>
                                form.setData(
                                    'sections',
                                    replaceItem(form.data.sections, index, {
                                        ...section,
                                        sort_order: Number(event.target.value),
                                    }),
                                )
                            }
                        />
                        <Field label="Judul ID">
                            <TextInput
                                value={section.title_id}
                                className="w-full"
                                onChange={(event) =>
                                    form.setData(
                                        'sections',
                                        replaceItem(form.data.sections, index, {
                                            ...section,
                                            title_id: event.target.value,
                                        }),
                                    )
                                }
                            />
                        </Field>
                        <Field label="Title EN">
                            <TextInput
                                value={section.title_en ?? ''}
                                className="w-full"
                                onChange={(event) =>
                                    form.setData(
                                        'sections',
                                        replaceItem(form.data.sections, index, {
                                            ...section,
                                            title_en: event.target.value,
                                        }),
                                    )
                                }
                            />
                        </Field>
                        <Field label="Style">
                            <TextInput
                                value={section.background_style}
                                className="w-full"
                                onChange={(event) =>
                                    form.setData(
                                        'sections',
                                        replaceItem(form.data.sections, index, {
                                            ...section,
                                            background_style: event.target.value,
                                        }),
                                    )
                                }
                            />
                        </Field>
                        <label className="flex items-center gap-2 text-sm font-semibold">
                            <Checkbox
                                checked={section.is_active}
                                onChange={(event) =>
                                    form.setData(
                                        'sections',
                                        replaceItem(form.data.sections, index, {
                                            ...section,
                                            is_active: event.target.checked,
                                        }),
                                    )
                                }
                            />
                            Aktif
                        </label>
                    </div>
                ))}
            </div>
            <PrimaryButton className="mt-5" disabled={form.processing}>
                Simpan Sections
            </PrimaryButton>
        </form>
    );
}

function QuickLinksForm({ quickLinks }: { quickLinks: AdminQuickLink[] }) {
    const form = useForm({ quick_links: quickLinks });
    const submit: FormEventHandler = (event) => {
        event.preventDefault();
        form.patch(route('admin.homepage.quick-links.update'), { preserveScroll: true });
    };

    return (
        <form
            onSubmit={submit}
            className="rounded-[var(--radius-md)] border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]"
        >
            <h2 className="text-lg font-bold">Quick Links</h2>
            <div className="mt-5 grid gap-4">
                {form.data.quick_links.map((link, index) => (
                    <div
                        key={link.key}
                        className="grid gap-3 rounded-md border border-brand-silver p-4 dark:border-slate-700 lg:grid-cols-[1fr_1fr_1.4fr_5rem_5rem]"
                    >
                        <TextInput
                            value={link.label_id}
                            onChange={(event) =>
                                form.setData(
                                    'quick_links',
                                    replaceItem(form.data.quick_links, index, {
                                        ...link,
                                        label_id: event.target.value,
                                    }),
                                )
                            }
                        />
                        <TextInput
                            value={link.label_en}
                            onChange={(event) =>
                                form.setData(
                                    'quick_links',
                                    replaceItem(form.data.quick_links, index, {
                                        ...link,
                                        label_en: event.target.value,
                                    }),
                                )
                            }
                        />
                        <TextInput
                            value={link.url}
                            onChange={(event) =>
                                form.setData(
                                    'quick_links',
                                    replaceItem(form.data.quick_links, index, {
                                        ...link,
                                        url: event.target.value,
                                    }),
                                )
                            }
                        />
                        <label className="flex items-center gap-2 text-sm font-semibold">
                            <Checkbox
                                checked={link.is_external}
                                onChange={(event) =>
                                    form.setData(
                                        'quick_links',
                                        replaceItem(form.data.quick_links, index, {
                                            ...link,
                                            is_external: event.target.checked,
                                        }),
                                    )
                                }
                            />
                            External
                        </label>
                        <label className="flex items-center gap-2 text-sm font-semibold">
                            <Checkbox
                                checked={link.is_active}
                                onChange={(event) =>
                                    form.setData(
                                        'quick_links',
                                        replaceItem(form.data.quick_links, index, {
                                            ...link,
                                            is_active: event.target.checked,
                                        }),
                                    )
                                }
                            />
                            Aktif
                        </label>
                    </div>
                ))}
            </div>
            <PrimaryButton className="mt-5" disabled={form.processing}>
                Simpan Quick Links
            </PrimaryButton>
        </form>
    );
}

function StatisticsForm({ statistics }: { statistics: AdminStatistic[] }) {
    const form = useForm({ statistics });
    const submit: FormEventHandler = (event) => {
        event.preventDefault();
        form.patch(route('admin.homepage.statistics.update'), { preserveScroll: true });
    };

    return (
        <form
            onSubmit={submit}
            className="rounded-[var(--radius-md)] border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]"
        >
            <h2 className="text-lg font-bold">Statistics</h2>
            <p className="mt-1 text-sm text-brand-graphite dark:text-slate-300">
                Statistik hanya tampil publik jika aktif dan statusnya verified.
            </p>
            <div className="mt-5 grid gap-4">
                {form.data.statistics.map((statistic, index) => (
                    <div
                        key={statistic.key}
                        className="grid gap-3 rounded-md border border-brand-silver p-4 dark:border-slate-700 lg:grid-cols-[1fr_6rem_5rem_8rem_5rem]"
                    >
                        <TextInput
                            value={statistic.label_id}
                            onChange={(event) =>
                                form.setData(
                                    'statistics',
                                    replaceItem(form.data.statistics, index, {
                                        ...statistic,
                                        label_id: event.target.value,
                                    }),
                                )
                            }
                        />
                        <TextInput
                            value={statistic.value ?? ''}
                            onChange={(event) =>
                                form.setData(
                                    'statistics',
                                    replaceItem(form.data.statistics, index, {
                                        ...statistic,
                                        value: event.target.value,
                                    }),
                                )
                            }
                        />
                        <TextInput
                            value={statistic.suffix ?? ''}
                            onChange={(event) =>
                                form.setData(
                                    'statistics',
                                    replaceItem(form.data.statistics, index, {
                                        ...statistic,
                                        suffix: event.target.value,
                                    }),
                                )
                            }
                        />
                        <TextInput
                            value={statistic.verification_status}
                            onChange={(event) =>
                                form.setData(
                                    'statistics',
                                    replaceItem(form.data.statistics, index, {
                                        ...statistic,
                                        verification_status: event.target.value,
                                    }),
                                )
                            }
                        />
                        <label className="flex items-center gap-2 text-sm font-semibold">
                            <Checkbox
                                checked={statistic.is_active}
                                onChange={(event) =>
                                    form.setData(
                                        'statistics',
                                        replaceItem(form.data.statistics, index, {
                                            ...statistic,
                                            is_active: event.target.checked,
                                        }),
                                    )
                                }
                            />
                            Aktif
                        </label>
                    </div>
                ))}
            </div>
            <PrimaryButton className="mt-5" disabled={form.processing}>
                Simpan Statistik
            </PrimaryButton>
        </form>
    );
}

function Field({ label, children }: { label: string; children: ReactNode }) {
    return (
        <div>
            <InputLabel value={label} />
            <div className="mt-1">{children}</div>
        </div>
    );
}

function replaceItem<T>(items: T[], index: number, value: T): T[] {
    return items.map((item, currentIndex) => (currentIndex === index ? value : item));
}
