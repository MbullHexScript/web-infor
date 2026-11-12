import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router, usePage } from '@inertiajs/react';

export default function ProfileDashboard({
    metrics,
    lecturers,
    pages,
    accreditation,
}: {
    metrics: Record<string, number>;
    lecturers: Record<string, string>[];
    pages: Record<string, string>[];
    accreditation?: Record<string, string>;
}) {
    const status = (usePage().props.flash as { status?: string } | undefined)?.status;
    return (
        <AuthenticatedLayout header={<h1 className="text-xl font-bold">Dashboard Profil</h1>}>
            <Head title="Dashboard Profil" />
            <div className="mx-auto grid max-w-7xl gap-6 p-6">
                {status && (
                    <div className="rounded-md bg-green-50 p-4 text-sm font-bold text-green-700">
                        {status}
                    </div>
                )}
                <div className="grid gap-4 md:grid-cols-3">
                    {Object.entries(metrics).map(([key, value]) => (
                        <div
                            key={key}
                            className="rounded-md bg-white p-5 shadow-sm dark:bg-[#1c2128]"
                        >
                            <div className="text-sm text-gray-500">{key}</div>
                            <div className="text-3xl font-black">{value}</div>
                        </div>
                    ))}
                </div>
                <button
                    onClick={() =>
                        router.post(route('admin.profile-module.lecturers.import-legacy'))
                    }
                    className="w-fit rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                >
                    Import Dosen Legacy
                </button>
                <section className="rounded-md bg-white p-6 shadow-sm dark:bg-[#1c2128]">
                    <h2 className="font-bold">Dosen</h2>
                    <div className="mt-3 grid gap-2">
                        {lecturers.map((lecturer) => (
                            <div key={lecturer.slug} className="rounded-md border p-3 text-sm">
                                {lecturer.full_name} - {lecturer.verification_status}
                            </div>
                        ))}
                    </div>
                </section>
                <section className="rounded-md bg-white p-6 shadow-sm dark:bg-[#1c2128]">
                    <h2 className="font-bold">Halaman Profil</h2>
                    <div className="mt-3 grid gap-2">
                        {pages.map((page) => (
                            <div key={page.key} className="rounded-md border p-3 text-sm">
                                {page.key} - {page.verification_status}
                            </div>
                        ))}
                    </div>
                </section>
                <section className="rounded-md bg-white p-6 shadow-sm dark:bg-[#1c2128]">
                    <h2 className="font-bold">Akreditasi</h2>
                    <p className="mt-2 text-sm">
                        {accreditation?.verification_status ??
                            'Belum ada data publik terverifikasi.'}
                    </p>
                </section>
            </div>
        </AuthenticatedLayout>
    );
}
