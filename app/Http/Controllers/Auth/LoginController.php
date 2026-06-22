<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse|Response
    {
        if (auth()->check()) {
            return redirect(auth()->user()->homeUrl());
        }

        return response()
            ->view('auth.login')
            ->withHeaders($this->noCacheHeaders());
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
        }

        if (! $user->canLogin()) {
            return back()->withErrors(['email' => 'Votre compte est suspendu ou archivé.'])->onlyInput('email');
        }

        $request->session()->put('login.id', $user->id);
        $request->session()->put('login.remember', $request->boolean('remember'));

        if ($user->two_factor_enabled && $user->two_factor_secret) {
            return redirect()->route('two-factor.challenge');
        }

        return $this->authenticateUser($request, $user);
    }

    public function authenticateUser(Request $request, User $user): RedirectResponse
    {
        Auth::login($user, $request->session()->get('login.remember', false));
        $request->session()->forget(['login.id', 'login.remember']);
        $request->session()->regenerate();

        return redirect()->intended($user->homeUrl());
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withHeaders($this->noCacheHeaders());
    }

    /** @return array<string, string> */
    private function noCacheHeaders(): array
    {
        return [
            'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0, private',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ];
    }

    public function showTwoFactorChallenge(): View|RedirectResponse
    {
        if (! session('login.id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function verifyTwoFactor(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $user = User::find(session('login.id'));

        if (! $user || ! $user->two_factor_secret) {
            return redirect()->route('login');
        }

        $google2fa = new Google2FA;
        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if (! $valid) {
            return back()->withErrors(['code' => 'Code de vérification invalide.']);
        }

        return $this->authenticateUser($request, $user);
    }
}
