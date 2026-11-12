import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import TextInput from '@/Components/TextInput';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

type TwoFactorProps = {
    enabled: boolean;
    secret: string;
    qrCodeUrl: string;
};

export default function TwoFactor({ enabled, secret, qrCodeUrl }: TwoFactorProps) {
    const { data, setData, post, processing, errors } = useForm({ code: '' });

    const submit: FormEventHandler = (event) => {
        event.preventDefault();
        post(route('admin.two-factor.enable'));
    };

    return (
        <AuthenticatedLayout
            header={<h1 className="font-heading text-2xl font-bold">Two-Factor Authentication</h1>}
        >
            <Head title="Two-Factor Authentication" />
            <div className="mx-auto max-w-3xl px-4 py-8 sm:px-6">
                <div className="rounded-[var(--radius-md)] border border-brand-silver bg-white p-6 dark:border-slate-700 dark:bg-[#1c2128]">
                    <p className="text-sm leading-6 text-brand-graphite dark:text-slate-300">
                        Pindai QR ini dengan aplikasi authenticator, lalu masukkan kode 6 digit
                        untuk mengaktifkan 2FA.
                    </p>
                    <div className="mt-5 overflow-hidden rounded-md border bg-white p-4">
                        <img
                            alt="QR code two-factor authentication"
                            src={`https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodeURIComponent(qrCodeUrl)}`}
                            className="h-[220px] w-[220px]"
                        />
                    </div>
                    <div className="bg-brand-lavender/20 mt-4 rounded-md p-3 font-mono text-sm text-brand-deep dark:text-white">
                        {secret}
                    </div>
                    {!enabled ? (
                        <form onSubmit={submit} className="mt-6">
                            <InputLabel htmlFor="code" value="Kode autentikasi" />
                            <TextInput
                                id="code"
                                value={data.code}
                                className="mt-1 block w-full"
                                inputMode="numeric"
                                onChange={(event) => setData('code', event.target.value)}
                            />
                            <InputError message={errors.code} className="mt-2" />
                            <PrimaryButton className="mt-5" disabled={processing}>
                                Aktifkan 2FA
                            </PrimaryButton>
                        </form>
                    ) : (
                        <SecondaryButton
                            className="mt-6"
                            onClick={() => router.delete(route('admin.two-factor.disable'))}
                        >
                            Nonaktifkan 2FA
                        </SecondaryButton>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
