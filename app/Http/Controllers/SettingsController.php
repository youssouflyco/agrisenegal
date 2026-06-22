<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function edit(Request $request)
    {
        return view('settings.edit', ['user' => $request->user()]);
    }

    public function profile(Request $request)
    {
        return view('settings.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user->update($data);

        return back()->with('status', 'Profil mis à jour.');
    }

    public function password(Request $request)
    {
        return view('settings.password', ['user' => $request->user()]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe incorrect.']);
        }

        $user->password = $data['password'];
        $user->save();

        return back()->with('status', 'Mot de passe mis à jour.');
    }

    public function delete(Request $request)
    {
        return view('settings.delete', ['user' => $request->user()]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        // Prevent admin/super-admin accounts from being deleted via this interface
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return back()->withErrors(['password' => 'Les comptes administrateurs ne peuvent pas être supprimés via cette interface.']);
        }

        $request->validate(['password' => ['required']]);

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $user->status = UserStatus::Archived;
        $user->archived_at = now();
        $user->save();

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Compte supprimé.');
    }
}
