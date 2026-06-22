<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->where('status', '!=', UserStatus::Archived)
            ->whereNull('archived_at')
            ->latest()
            ->paginate(15);

        return view('super-admin.utilisateurs.index', compact('users'));
    }

    public function create(): View
    {
        return view('super-admin.utilisateurs.create', [
            'roles' => UserRole::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(array_map(fn (UserRole $role) => $role->value, UserRole::cases()))],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'status' => UserStatus::Active,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'Utilisateur ajouté avec succès.');
    }

    public function edit(User $user): View
    {
        return view('super-admin.utilisateurs.edit', [
            'user' => $user,
            'roles' => UserRole::cases(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(array_map(fn (UserRole $role) => $role->value, UserRole::cases()))],
        ]);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        $user->update($updateData);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'Utilisateur mis à jour.');
    }

    public function bloquer(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => UserStatus::Suspended,
            'suspended_at' => now(),
        ]);

        return back()->with('success', 'Utilisateur bloqué.');
    }

    public function activer(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => UserStatus::Active,
            'suspended_at' => null,
        ]);

        return back()->with('success', 'Utilisateur activé.');
    }

    public function archiver(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => UserStatus::Archived,
            'archived_at' => now(),
        ]);

        return back()->with('success', 'Utilisateur archivé.');
    }

    public function archives(): View
    {
        $users = User::query()
            ->where('status', UserStatus::Archived)
            ->latest('archived_at')
            ->paginate(15);

        return view('super-admin.utilisateurs.archives', compact('users'));
    }

    public function restaurer(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => UserStatus::Active,
            'archived_at' => null,
            'suspended_at' => null,
        ]);

        return back()->with('success', 'Utilisateur restauré.');
    }
}
