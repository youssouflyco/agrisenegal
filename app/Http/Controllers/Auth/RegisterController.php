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

    private const REGION_COORDINATES = [
        'Dakar' => [14.7167, -17.4677],
        'Diourbel' => [14.6608, -16.2397],
        'Fatick' => [14.339, -16.412],
        'Kaffrine' => [14.1058, -15.5492],
        'Kaolack' => [14.138, -16.076],
        'Kédougou' => [12.559, -12.187],
        'Kolda' => [12.8939, -14.941],
        'Louga' => [15.614, -16.228],
        'Matam' => [15.655, -13.255],
        'Saint-Louis' => [16.0179, -16.4896],
        'Sédhiou' => [12.708, -15.556],
        'Tambacounda' => [13.7709, -13.6673],
        'Thiès' => [14.791, -16.925],
        'Ziguinchor' => [12.5681, -16.273],
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
        'producteur' => ['champ', 'magasin', 'boutique'],
        'distributeur' => ['champ', 'magasin', 'boutique'],
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
            'cni' => ['required', 'string', 'max:50', 'unique:users,cni'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'cni_front_photo' => [in_array($role, [UserRole::Producer, UserRole::Distributor], true) ? 'required' : 'nullable', 'image', 'max:2048'],
            'cni_back_photo' => [in_array($role, [UserRole::Producer, UserRole::Distributor], true) ? 'required' : 'nullable', 'image', 'max:2048'],
            'farm_name' => [$role === UserRole::Producer ? 'required' : 'nullable', 'string', 'max:255'],
            'production_type' => [$role === UserRole::Producer ? 'required' : 'nullable', 'string', 'max:255'],
            'farm_photo' => ['nullable', 'image', 'max:2048'],
            'business_name' => [$role === UserRole::Distributor ? 'required' : 'nullable', 'string', 'max:255'],
            'business_type' => [$role === UserRole::Distributor ? 'required' : 'nullable', 'string', 'max:255'],
            'business_photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'location_name' => [$requiresLocation ? 'required' : 'nullable', 'string', 'max:255'],
            'location_region' => [$requiresLocation ? 'required' : 'nullable', 'string', 'max:120'],
            'location_type' => [$requiresLocation ? 'required' : 'nullable', 'string', 'in:' . implode(',', self::LOCATION_TYPES[$type])],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'location_visible_publicly' => ['sometimes', 'boolean'],
        ]);

        $photoPath = $request->file('photo')?->store('users/photos', 'public');
        $cniFrontPath = $request->file('cni_front_photo')?->store('users/cni', 'public');
        $cniBackPath = $request->file('cni_back_photo')?->store('users/cni', 'public');
        $farmPhotoPath = $request->file('farm_photo')?->store('users/business', 'public');
        $businessPhotoPath = $request->file('business_photo')?->store('users/business', 'public');

        $user = User::create([
            'name' => "{$data['first_name']} {$data['last_name']}",
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'cni' => $data['cni'],
            'cni_front_photo' => $cniFrontPath,
            'cni_back_photo' => $cniBackPath,
            'farm_name' => $data['farm_name'] ?? null,
            'production_type' => $data['production_type'] ?? null,
            'farm_photo' => $farmPhotoPath,
            'business_name' => $data['business_name'] ?? null,
            'business_type' => $data['business_type'] ?? null,
            'business_photo' => $businessPhotoPath,
            'photo' => $photoPath,
            'password' => $data['password'],
            'role' => $role,
            'status' => in_array($role, [UserRole::Producer, UserRole::Distributor], true) ? UserStatus::Pending : UserStatus::Active,
            'email_verified_at' => in_array($role, [UserRole::Producer, UserRole::Distributor], true) ? null : now(),
        ]);

        if ($requiresLocation) {
            [$latitude, $longitude] = $this->regionCoordinates($data['location_region']);

            $user->locations()->create([
                'label' => $data['location_name'],
                'region' => $data['location_region'],
                'kind' => $data['location_type'] ?? 'champ',
                'latitude' => $latitude,
                'longitude' => $longitude,
                'is_primary' => true,
                'publicly_visible' => $request->boolean('location_visible_publicly', true),
            ]);
        } elseif (! empty($data['location_name']) && isset($data['latitude'], $data['longitude'])) {
            $user->locations()->create([
                'label' => $data['location_name'],
                'region' => $data['location_region'] ?? null,
                'kind' => $data['location_type'] ?? 'maison',
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'is_primary' => true,
                'publicly_visible' => $request->boolean('location_visible_publicly', true),
            ]);
        }

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended($user->homeUrl());
    }

    private function regionCoordinates(string $region): array
    {
        return self::REGION_COORDINATES[$region] ?? self::REGION_COORDINATES['Dakar'];
    }
}
