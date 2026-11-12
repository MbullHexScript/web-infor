import {
    ImportFileStep,
    ImportMappingStep,
    ImportPreviewStep,
    ImportResultStep,
    ImportValidationStep,
    ImportWizard,
} from '@/Components/Academic/AcademicComponents';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm, usePage } from '@inertiajs/react';

export default function CurriculumImports({
    imports,
    defaultPath,
}: {
    imports: {
        data: {
            id: number;
            mode: string;
            status: string;
            rows_processed: number;
            warnings_count: number;
            errors_count: number;
        }[];
    };
    defaultPath: string;
}) {
    const page = usePage();
    const form = useForm({ path: defaultPath });
    const status = (page.props.flash as { status?: string } | undefined)?.status;
    return (
        <AuthenticatedLayout header={<h1 className="text-xl font-bold">Import Kurikulum</h1>}>
            <Head title="Import Kurikulum" />
            <div className="mx-auto grid max-w-7xl gap-6 p-6">
                {status && (
                    <div className="rounded-md bg-green-50 p-4 text-sm font-bold text-green-700">
                        {status}
                    </div>
                )}
                <ImportWizard>
                    <ImportFileStep>
                        <label className="grid gap-2 text-sm font-bold">
                            Path XLSX
                            <input
                                value={form.data.path}
                                onChange={(event) => form.setData('path', event.target.value)}
                                className="rounded-md border-gray-300 dark:bg-[#11151a]"
                            />
                        </label>
                        <div className="mt-4 flex gap-3">
                            <button
                                onClick={() => form.post(route('admin.academic.imports.inspect'))}
                                className="rounded-md border px-4 py-2 text-sm font-bold"
                            >
                                Inspect / Dry-run
                            </button>
                            <button
                                onClick={() => form.post(route('admin.academic.imports.commit'))}
                                className="rounded-md bg-brand-deep px-4 py-2 text-sm font-bold text-white"
                            >
                                Commit Import
                            </button>
                        </div>
                    </ImportFileStep>
                    <ImportMappingStep />
                    <ImportValidationStep />
                    <ImportPreviewStep />
                    <ImportResultStep />
                </ImportWizard>
                <div className="rounded-md bg-white p-6 shadow-sm dark:bg-[#1c2128]">
                    <h2 className="font-bold">Riwayat Import</h2>
                    <div className="mt-3 grid gap-2">
                        {imports.data.map((item) => (
                            <div key={item.id} className="rounded-md border p-3 text-sm">
                                #{item.id} {item.mode} - {item.status}, rows {item.rows_processed},
                                warnings {item.warnings_count}, errors {item.errors_count}
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
