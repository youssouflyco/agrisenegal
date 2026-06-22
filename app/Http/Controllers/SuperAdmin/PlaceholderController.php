<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class PlaceholderController extends Controller
{
    public function show(string $section): View
    {
        $titles = [
            'users' => 'Utilisateurs',
            'producers' => 'Producteurs',
            'distributors' => 'Distributeurs',
            'products' => 'Produits',
            'orders' => 'Commandes',
            'claims' => 'Réclamations',
            'withdrawals' => 'Retraits',
            'locations' => 'Localisations',
            'notifications' => 'Notifications',
            'settings' => 'Paramètres',
        ];

        if (in_array($section, ['users', 'producers', 'distributors'], true)) {
            $query = User::query()
                ->where('status', '!=', UserStatus::Archived)
                ->whereNull('archived_at')
                ->latest();

            if ($section === 'producers') {
                $query->where('role', UserRole::Producer);
            } elseif ($section === 'distributors') {
                $query->where('role', UserRole::Distributor);
            }

            return view('super-admin.users-directory', [
                'title' => $titles[$section] ?? ucfirst($section),
                'section' => $section,
                'users' => $query->paginate(15),
                'description' => match ($section) {
                    'producers' => 'Consultez les producteurs enregistrés et gérez leurs comptes.',
                    'distributors' => 'Consultez les distributeurs enregistrés et gérez leurs comptes.',
                    default => 'Consultez tous les comptes actifs et bloqués de la plateforme.',
                },
            ]);
        }

        return view('super-admin.placeholder', [
            'title' => $titles[$section] ?? ucfirst($section),
            'section' => $section,
        ]);
    }
}
