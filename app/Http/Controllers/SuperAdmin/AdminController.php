<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\User;
use App\Services\AdminActivityLogger;
use App\Services\TableExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery($request);

        $admins = $query->paginate(10)->withQueryString();

        return view('super-admin.admins.index', compact('admins'));
    }

    public function create(): View
    {
        return view('super-admin.admins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $photoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('admins/photos', 'public')
            : null;

        $admin = User::create([
            'name' => "{$data['first_name']} {$data['last_name']}",
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'photo' => $photoPath,
            'password' => $data['password'],
            'role' => UserRole::Admin,
            'status' => UserStatus::Active,
            'created_by' => auth()->id(),
        ]);

        AdminActivityLogger::log(
            auth()->user(),
            'admin_created',
            "Administrateur {$admin->full_name}",
            User::class,
            $admin->id,
        );

        return redirect()->route('super-admin.admins.index')
            ->with('success', 'Administrateur créé avec succès.');
    }

    public function show(User $admin): View
    {
        $this->ensureAdmin($admin);

        $activities = AdminActivityLog::where('admin_id', $admin->id)
            ->latest()
            ->paginate(15);

        return view('super-admin.admins.show', compact('admin', 'activities'));
    }

    public function edit(User $admin): View
    {
        $this->ensureAdmin($admin);

        return view('super-admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        $this->ensureAdmin($admin);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email,'.$admin->id],
            'phone' => ['required', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('admins/photos', 'public');
        }

        $admin->update([
            'name' => "{$data['first_name']} {$data['last_name']}",
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'photo' => $data['photo'] ?? $admin->photo,
        ]);

        AdminActivityLogger::log(
            auth()->user(),
            'admin_updated',
            "Administrateur {$admin->full_name}",
            User::class,
            $admin->id,
        );

        return redirect()->route('super-admin.admins.index')
            ->with('success', 'Administrateur mis à jour.');
    }

    public function resetPassword(User $admin): RedirectResponse
    {
        $this->ensureAdmin($admin);

        $password = Str::password(12);
        $admin->update(['password' => $password]);

        AdminActivityLogger::log(
            auth()->user(),
            'admin_password_reset',
            "Administrateur {$admin->full_name}",
            User::class,
            $admin->id,
        );

        return back()->with('success', "Mot de passe réinitialisé : {$password}");
    }

    public function suspend(Request $request, User $admin): RedirectResponse
    {
        $this->ensureAdmin($admin);

        $request->validate(['reason' => ['nullable', 'string', 'max:500']]);

        $admin->update([
            'status' => UserStatus::Suspended,
            'suspended_at' => now(),
        ]);

        AdminActivityLogger::log(
            auth()->user(),
            'admin_suspended',
            "Administrateur {$admin->full_name}",
            User::class,
            $admin->id,
            $request->reason,
        );

        return back()->with('success', 'Administrateur suspendu.');
    }

    public function activate(User $admin): RedirectResponse
    {
        $this->ensureAdmin($admin);

        $admin->update([
            'status' => UserStatus::Active,
            'suspended_at' => null,
        ]);

        AdminActivityLogger::log(
            auth()->user(),
            'admin_activated',
            "Administrateur {$admin->full_name}",
            User::class,
            $admin->id,
        );

        return back()->with('success', 'Administrateur réactivé.');
    }

    public function archive(Request $request, User $admin): RedirectResponse
    {
        $this->ensureAdmin($admin);

        $request->validate(['reason' => ['nullable', 'string', 'max:500']]);

        $admin->update([
            'status' => UserStatus::Archived,
            'archived_at' => now(),
        ]);

        AdminActivityLogger::log(
            auth()->user(),
            'admin_archived',
            "Administrateur {$admin->full_name}",
            User::class,
            $admin->id,
            $request->reason,
        );

        return back()->with('success', 'Administrateur archivé.');
    }

    public function export(Request $request, TableExportService $export, string $format)
    {
        $admins = $this->filteredQuery($request)->get();
        $headers = ['Prénom', 'Nom', 'Email', 'Téléphone', 'Statut', 'Créé le'];
        $rows = $admins->map(fn ($a) => [
            $a->first_name,
            $a->last_name,
            $a->email,
            $a->phone,
            $a->status->label(),
            $a->created_at->format('d/m/Y H:i'),
        ]);

        if ($format === 'pdf') {
            return $export->toPdf('exports.table', [
                'title' => 'Liste des administrateurs',
                'headers' => $headers,
                'rows' => $rows,
            ], 'administrateurs');
        }

        return $export->toExcel($headers, $rows, 'administrateurs');
    }

    private function filteredQuery(Request $request)
    {
        $query = User::admins()->with('creator')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $sort = $request->get('sort', 'created_at');
        $dir = $request->get('dir', 'desc');
        $allowed = ['first_name', 'last_name', 'email', 'created_at', 'status'];

        if (in_array($sort, $allowed, true)) {
            $query->orderBy($sort, $dir === 'asc' ? 'asc' : 'desc');
        }

        return $query;
    }

    private function ensureAdmin(User $admin): void
    {
        if ($admin->role !== UserRole::Admin) {
            abort(404);
        }
    }
}
