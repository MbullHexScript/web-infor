import { MessageStatusBadge } from '@/Components/StudentLife/StudentLifeComponents';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';

type Message = {
    id: number;
    name: string;
    email: string;
    category: string;
    subject: string;
    message: string;
    status: string;
    created_at: string;
};

type Props = {
    metrics: Record<string, number>;
    filters: Record<string, string>;
    items: {
        data: Message[];
        links: { url: string | null; label: string; active: boolean }[];
    };
};

export default function ContactInbox({ metrics, items }: Props) {
    return (
        <AuthenticatedLayout
            header={
                <div>
                    <h1 className="text-2xl font-black text-brand-deep dark:text-white">
                        Admin Kontak
                    </h1>
                    <p className="mt-1 text-sm text-brand-graphite dark:text-slate-300">
                        Pesan masuk, status tindak lanjut, arsip, dan spam.
                    </p>
                </div>
            }
        >
            <Head title="Admin Kontak" />
            <div className="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6">
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    {Object.entries(metrics).map(([key, value]) => (
                        <div
                            key={key}
                            className="rounded-md border border-brand-silver bg-white p-4 dark:border-slate-700 dark:bg-[#1c2128]"
                        >
                            <div className="text-2xl font-black">{value}</div>
                            <div className="text-xs font-bold uppercase">{key}</div>
                        </div>
                    ))}
                </div>
                <a
                    href={route('admin.contact.messages.export')}
                    className="w-fit rounded-md border border-brand-silver px-4 py-3 text-sm font-bold dark:border-slate-700"
                >
                    Export CSV aman
                </a>
                <section className="grid gap-4">
                    {items.data.length === 0 && (
                        <div className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]">
                            Belum ada pesan.
                        </div>
                    )}
                    {items.data.map((message) => (
                        <article
                            key={message.id}
                            className="rounded-md border border-brand-silver bg-white p-5 dark:border-slate-700 dark:bg-[#1c2128]"
                        >
                            <div className="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h2 className="text-lg font-black">{message.subject}</h2>
                                    <p className="text-sm">
                                        {message.name} | {message.email} | {message.category}
                                    </p>
                                </div>
                                <MessageStatusBadge status={message.status} />
                            </div>
                            <p className="mt-4 whitespace-pre-wrap text-sm leading-6 text-brand-graphite dark:text-slate-300">
                                {message.message}
                            </p>
                            <div className="mt-4 flex flex-wrap gap-2">
                                {['read', 'replied', 'archived', 'spam'].map((status) => (
                                    <button
                                        key={status}
                                        onClick={() =>
                                            router.post(
                                                route('admin.contact.messages.status', {
                                                    message: message.id,
                                                    status,
                                                }),
                                            )
                                        }
                                        className="rounded-md border border-brand-silver px-3 py-2 text-xs font-bold dark:border-slate-700"
                                    >
                                        {status}
                                    </button>
                                ))}
                                <button
                                    onClick={() =>
                                        router.delete(
                                            route('admin.contact.messages.delete', message.id),
                                        )
                                    }
                                    className="rounded-md border border-red-300 px-3 py-2 text-xs font-bold text-red-700"
                                >
                                    delete
                                </button>
                            </div>
                        </article>
                    ))}
                </section>
            </div>
        </AuthenticatedLayout>
    );
}
