<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\UserLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LocationController extends Controller
{
    private const REGION_OPTIONS = [
        'Dakar', 'Diourbel', 'Fatick', 'Kaffrine', 'Kaolack', 'Kédougou', 'Kolda',
        'Louga', 'Matam', 'Saint-Louis', 'Sédhiou', 'Tambacounda', 'Thiès', 'Ziguinchor',
    ];

    private const LOCATION_TYPES = [
        'client' => ['maison', 'travail', 'autre'],
        'producer' => ['champ', 'ferme', 'autre'],
        'distributor' => ['boutique', 'depot', 'autre'],
    ];

    public function index(Request $request): View
    {
        $user = $request->user();

        return view('locations.index', [
            'user' => $user,
            'locations' => $user->locations()->latest('is_primary')->latest()->get(),
            'locationTypes' => $this->locationTypesFor($user),
            'regionOptions' => self::REGION_OPTIONS,
        ]);
    }

    public function create(Request $request): View
    {
        $user = $request->user();

        return view('locations.create', [
            'user' => $user,
            'location' => new UserLocation(['publicly_visible' => $user->role !== UserRole::Client]),
            'locationTypes' => $this->locationTypesFor($user),
            'regionOptions' => self::REGION_OPTIONS,
            'action' => route('locations.store'),
            'method' => 'POST',
            'buttonLabel' => 'Ajouter la localisation',
            'title' => 'Ajouter une localisation',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $this->validatedLocationData($request, $user);

        $location = DB::transaction(function () use ($user, $data) {
            $location = $user->locations()->create([
                'label' => $data['label'],
                'region' => $data['region'] ?? null,
                'kind' => $data['kind'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'publicly_visible' => $data['publicly_visible'],
                'is_primary' => $data['is_primary'] ?? false,
            ]);

            if ($user->locations()->count() === 1 || $location->is_primary) {
                $this->makePrimary($user, $location);
            }

            return $location;
        });

        return redirect()->route('locations.index')->with('success', "La localisation « {$location->label} » a été ajoutée.");
    }

    public function edit(Request $request, UserLocation $location): View
    {
        $this->authorizeLocation($request->user(), $location);

        return view('locations.edit', [
            'user' => $request->user(),
            'location' => $location,
            'locationTypes' => $this->locationTypesFor($request->user()),
            'regionOptions' => self::REGION_OPTIONS,
            'action' => route('locations.update', $location),
            'method' => 'PUT',
            'buttonLabel' => 'Enregistrer les changements',
            'title' => 'Modifier la localisation',
        ]);
    }

    public function update(Request $request, UserLocation $location): RedirectResponse
    {
        $this->authorizeLocation($request->user(), $location);
        $data = $this->validatedLocationData($request, $location->user);
        $wasPrimary = $location->is_primary;

        DB::transaction(function () use ($location, $data, $wasPrimary) {
            $location->update([
                'label' => $data['label'],
                'region' => $data['region'] ?? null,
                'kind' => $data['kind'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'publicly_visible' => $data['publicly_visible'],
                'is_primary' => $data['is_primary'] ?? false,
            ]);

            if ($location->is_primary) {
                $this->makePrimary($location->user, $location);
                return;
            }

            if ($wasPrimary) {
                $fallback = $location->user->locations()->whereKeyNot($location->id)->orderByDesc('created_at')->first();

                if ($fallback) {
                    $this->makePrimary($location->user, $fallback);
                } else {
                    $location->update(['is_primary' => true]);
                }
            }
        });

        return redirect()->route('locations.index')->with('success', 'La localisation a été mise à jour.');
    }

    public function destroy(Request $request, UserLocation $location): RedirectResponse
    {
        $this->authorizeLocation($request->user(), $location);
        $user = $location->user;
        $wasPrimary = $location->is_primary;

        $location->delete();

        if ($wasPrimary) {
            $fallback = $user->locations()->orderByDesc('created_at')->first();

            if ($fallback) {
                $this->makePrimary($user, $fallback);
            }
        }

        return redirect()->route('locations.index')->with('success', 'La localisation a été supprimée.');
    }

    public function primary(Request $request, UserLocation $location): RedirectResponse
    {
        $this->authorizeLocation($request->user(), $location);
        $this->makePrimary($location->user, $location);

        return back()->with('success', 'La localisation principale a été mise à jour.');
    }

    private function validatedLocationData(Request $request, User $user): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'region' => ['nullable', 'string', Rule::in(self::REGION_OPTIONS)],
            'kind' => ['required', 'string', Rule::in($this->locationTypesFor($user))],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'publicly_visible' => ['sometimes', 'boolean'],
            'is_primary' => ['sometimes', 'boolean'],
        ]);
    }

    private function locationTypesFor(User $user): array
    {
        return self::LOCATION_TYPES[$user->role->value] ?? ['autre'];
    }

    private function authorizeLocation(?User $viewer, UserLocation $location): void
    {
        abort_unless(
            $viewer && ($viewer->id === $location->user_id || $viewer->isAdmin() || $viewer->isSuperAdmin()),
            403
        );
    }

    private function makePrimary(User $user, UserLocation $location): void
    {
        $user->locations()->whereKeyNot($location->id)->update(['is_primary' => false]);
        $location->update(['is_primary' => true]);
    }
}