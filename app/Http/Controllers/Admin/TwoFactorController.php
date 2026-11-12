<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorController extends Controller
{
    public function challenge(): Response
    {
        return Inertia::render('Auth/TwoFactorChallenge', [
            'status' => session('status'),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        if (! app('pragmarx.google2fa')->verifyKey($request->user()->two_factor_secret, $request->string('code')->toString())) {
            throw ValidationException::withMessages([
                'code' => __('Kode autentikasi tidak valid.'),
            ]);
        }

        $request->session()->put('auth.two_factor_passed', true);

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    public function edit(Request $request): Response
    {
        $user = $request->user();
        $secret = $user->two_factor_secret ?: app('pragmarx.google2fa')->generateSecretKey();

        if (! $user->two_factor_secret) {
            $user->forceFill(['two_factor_secret' => $secret])->save();
        }

        $qrCodeUrl = app('pragmarx.google2fa')->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret,
        );

        return Inertia::render('Admin/TwoFactor', [
            'enabled' => $user->hasEnabledTwoFactorAuthentication(),
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    public function enable(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        if (! app('pragmarx.google2fa')->verifyKey($request->user()->two_factor_secret, $request->string('code')->toString())) {
            throw ValidationException::withMessages([
                'code' => __('Kode autentikasi tidak valid.'),
            ]);
        }

        $request->user()->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->put('auth.two_factor_passed', true);

        return back()->with('status', __('2FA berhasil diaktifkan.'));
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $request->session()->forget('auth.two_factor_passed');

        return back()->with('status', __('2FA dinonaktifkan.'));
    }
}
