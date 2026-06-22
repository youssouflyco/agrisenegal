<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AdminActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function setup(Request $request): View
    {
        $user = $request->user();
        $google2fa = new Google2FA;
        $secret = $google2fa->generateSecretKey();

        $request->session()->put('two_factor.setup_secret', $secret);

        $qrUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('auth.two-factor-setup', [
            'secret' => $secret,
            'qrUrl' => $qrUrl,
        ]);
    }

    public function confirm(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $secret = $request->session()->get('two_factor.setup_secret');
        $google2fa = new Google2FA;

        if (! $secret || ! $google2fa->verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'Code invalide.']);
        }

        $user = $request->user();
        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_enabled' => true,
            'two_factor_confirmed_at' => now(),
        ]);

        $request->session()->forget('two_factor.setup_secret');

        return redirect()->route('settings.edit')
            ->with('success', 'Double authentification activée.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required']]);

        if (! auth()->validate(['email' => $request->user()->email, 'password' => $request->password])) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $request->user()->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
            'two_factor_confirmed_at' => null,
        ]);

        return back()->with('success', 'Double authentification désactivée.');
    }
}
