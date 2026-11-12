import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm, usePage } from '@inertiajs/react';

type CourseOption = { id: number; code: string; name_id: string; semester: number };

export default function RpsIndex({
    courses,
    rpsDocuments,
}: {
    courses: CourseOption[];
    rpsDocuments: { data: Record<string, string | number>[] };
}) {
    const page = usePage();
    const status = (page.props.flash as { status?: string } | undefined)?.status;
    const form = useForm({
        course_id: '',
        title_id: '',
        title_en: '',
        version: 'v1',
        academic_year: '2026/2027',
        semester_type: 'ganjil',
        coordinator_name: '',
        approved_date: '',
        is_public: true,
        is_current: true,
        file: null as File | null,
    });

    return (
        <AuthenticatedLayout header={<h1 className="text-xl font-bold">RPS</h1>}>
            <Head title="RPS" />
            <div className="mx-auto grid max-w-7xl gap-6 p-6">
                {status && (
                    <div className="rounded-md bg-green-50 p-4 text-sm font-bold text-green-700">
                        {status}
                    </div>
                )}
                <form
                    onSubmit={(event) => {
                        event.preventDefault();
                        form.post(route('admin.rps.store'), { forceFormData: true });
                    }}
                    className="grid gap-4 rounded-md bg-white p-6 shadow-sm dark:bg-[#1c2128]"
                >
                    <select
                        value={form.data.course_id}
                        onChange={(event) => form.setData('course_id', event.target.value)}
                        className="rounded-md border-gray-300 dark:bg-[#11151a]"
                    >
                        <option value="">Pilih mata kuliah</option>
                        {courses.map((course) => (
                            <option key={course.id} value={course.id}>
                                {course.code} - {course.name_id}
                            </option>
                        ))}
                    </select>
                    <input
                        placeholder="Judul RPS"
                        value={form.data.title_id}
                        onChange={(event) => form.setData('title_id', event.target.value)}
                        className="rounded-md border-gray-300 dark:bg-[#11151a]"
                    />
                    <div className="grid gap-3 md:grid-cols-3">
                        <input
                            placeholder="Versi"
                            value={form.data.version}
                            onChange={(event) => form.setData('version', event.target.value)}
                            className="rounded-md border-gray-300 dark:bg-[#11151a]"
                        />
                        <input
                            placeholder="Tahun akademik"
                            value={form.data.academic_year}
                            onChange={(event) => form.setData('academic_year', event.target.value)}
                            className="rounded-md border-gray-300 dark:bg-[#11151a]"
                        />
                        <input
                            type="date"
                            value={form.data.approved_date}
                            onChange={(event) => form.setData('approved_date', event.target.value)}
                            className="rounded-md border-gray-300 dark:bg-[#11151a]"
                        />
                    </div>
                    <input
                        type="file"
                        accept="application/pdf"
                        onChange={(event) => form.setData('file', event.target.files?.[0] ?? null)}
                    />
                    <button className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white">
                        Upload RPS
                    </button>
                </form>
                <div className="rounded-md bg-white p-6 shadow-sm dark:bg-[#1c2128]">
                    <h2 className="font-bold">Dokumen RPS</h2>
                    <div className="mt-3 grid gap-2">
                        {rpsDocuments.data.map((item) => (
                            <div key={String(item.id)} className="rounded-md border p-3 text-sm">
                                {String(item.code ?? '')} - {String(item.title_id)} -{' '}
                                {String(item.status)}
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
