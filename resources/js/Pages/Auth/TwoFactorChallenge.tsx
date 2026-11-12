import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

export default function TwoFactorChallenge() {
    const { data, setData, post, processing, errors } = useForm({ code: '' });

    const submit: FormEventHandler = (event) => {
        event.preventDefault();
        post(route('two-factor.verify'));
    };

    return (
        <GuestLayout>
            <Head title="Two-Factor Authentication" />
            <h1 className="mb-2 font-heading text-2xl font-bold">Verifikasi 2FA</h1>
            <p className="mb-6 text-sm text-gray-600">
                Masukkan kode 6 digit dari aplikasi authenticator.
            </p>
            <form onSubmit={submit}>
                <InputLabel htmlFor="code" value="Kode autentikasi" />
                <TextInput
                    id="code"
                    value={data.code}
                    className="mt-1 block w-full"
                    inputMode="numeric"
                    autoComplete="one-time-code"
                    onChange={(event) => setData('code', event.target.value)}
                />
                <InputError message={errors.code} className="mt-2" />
                <PrimaryButton className="mt-5 w-full justify-center" disabled={processing}>
                    Verifikasi
                </PrimaryButton>
            </form>
        </GuestLayout>
    );
}
