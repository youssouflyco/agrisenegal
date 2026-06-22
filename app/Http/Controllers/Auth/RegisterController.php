<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    private const ALLOWED_ROLES = [
        'client' => UserRole::Client,
        'producteur' => UserRole::Producer,
        'distributeur' => UserRole::Distributor,
    ];

    private const REGION_OPTIONS = [
        'Dakar',
        'Diourbel',
        'Fatick',
        'Kaffrine',
        'Kaolack',
        'Kédougou',
        'Kolda',
        'Louga',
        'Matam',
        'Saint-Louis',
        'Sédhiou',
        'Tambacounda',
        'Thiès',
        'Ziguinchor',
    ];

    private const LOCATION_TYPES = [
        'client' => ['maison', 'travail', 'ferme', 'autre'],
        'producteur' => ['champ', 'ferme', 'autre'],
        'distributeur' => ['boutique', 'depot', 'autre'],
    ];

    public function showRegistrationHub(): View
    {
        return view('auth.register-hub');
    }

    public function showRegistrationForm(string $type): View|RedirectResponse
    {
        if (! isset(self::ALLOWED_ROLES[$type])) {
            return redirect()->route('register');
        }

        return view('auth.register', [
            'type' => $type,
            'role' => self::ALLOWED_ROLES[$type],
            'roleLabel' => self::ALLOWED_ROLES[$type]->label(),
            'regionOptions' => self::REGION_OPTIONS,
            'locationTypes' => self::LOCATION_TYPES[$type],
        ]);
    }

    public function register(Request $request, string $type): RedirectResponse
    {
        if (! isset(self::ALLOWED_ROLES[$type])) {
            return redirect()->route('register');
        }

        $role = self::ALLOWED_ROLES[$type];
        $requiresLocation = in_array($role, [UserRole::Producer, UserRole::Distributor], true);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'location_name' => [$requiresLocation ? 'required' : 'nullable', 'string', 'max:255'],
            'location_region' => [$requiresLocation ? 'required' : 'nullable', 'string', 'max:120'],
            'location_type' => [$requiresLocation ? 'required' : 'nullable', 'string', 'in:' . implode(',', self::LOCATION_TYPES[$type])],
            'latitude' => [$requiresLocation ? 'required' : 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => [$requiresLocation ? 'required' : 'nullable', 'numeric', 'between:-180,180'],
            'location_visible_publicly' => ['sometimes', 'boolean'],
        ]);

        $user = User::create([
            'name' => "{$data['first_name']} {$data['last_name']}",
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'role' => $role,
            'status' => UserStatus::Active,
            'email_verified_at' => now(),
        ]);

        if (! empty($data['location_name']) && isset($data['latitude'], $data['longitude'])) {
            $user->locations()->create([
                'label' => $data['location_name'],
                'region' => $data['location_region'] ?? null,
                'kind' => $data['location_type'] ?? ($role === UserRole::Producer ? 'champ' : ($role === UserRole::Distributor ? 'boutique' : 'maison')),
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'is_primary' => true,
                'publicly_visible' => $role !== UserRole::Client || $request->boolean('location_visible_publicly'),
            ]);
        }

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect($user->homeUrl());
    }
}
