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
            'products' => 'Produits',
            'orders' => 'Commandes',
            'claims' => 'Réclamations',
            'withdrawals' => 'Retraits',
            'locations' => 'Localisations',
            'notifications' => 'Notifications',
            'settings' => 'Paramètres',
        ];

        $query = User::query()->latest();

            return view('super-admin.users-directory', [
                'title' => $titles[$section] ?? ucfirst($section),
                'section' => $section,
                'users' => $query->paginate(15),
                'description' => match ($section) {
                    default => 'Consultez tous les comptes actifs et suspendus de la plateforme.',
                },
            ]);
    }
}

