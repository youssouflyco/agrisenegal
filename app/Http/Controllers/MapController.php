<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapController extends Controller
{
    public function index(Request $request): View
    {
        $viewer = $request->user();
        $radius = $request->filled('radius') ? (float) $request->input('radius') : null;
        $filter = $request->input('filter', 'all');

        $viewerLocation = $viewer?->locations()->where('is_primary', true)->first();
        $viewerCoordinates = $viewerLocation ? [$viewerLocation->latitude, $viewerLocation->longitude] : null;

        $vendors = User::query()
            ->whereIn('role', [UserRole::Producer->value, UserRole::Distributor->value])
            ->with(['locations' => fn ($query) => $query->orderByDesc('is_primary')])
            ->withCount('products')
            ->get()
            ->flatMap(function (User $vendor) use ($viewer, $viewerCoordinates) {
                return $vendor->locations->map(function (UserLocation $location) use ($vendor, $viewer, $viewerCoordinates) {
                    $distance = $viewerCoordinates ? round($location->distanceTo($viewerCoordinates[0], $viewerCoordinates[1]), 1) : null;

                    return [
                        'vendor_id' => $vendor->id,
                        'vendor_role' => $vendor->role->value,
                        'vendor_role_label' => $vendor->role->label(),
                        'vendor_name' => $vendor->full_name,
                        'vendor_photo' => $vendor->photo_url,
                        'vendor_phone' => $vendor->phone,
                        'vendor_email' => $vendor->email,
                        'product_count' => $vendor->products_count,
                        'location_id' => $location->id,
                        'location_label' => $location->label,
                        'region' => $location->region,
                        'kind' => $location->kind,
                        'kind_label' => $location->kindLabel(),
                        'coordinates' => $location->coordinatesFor($viewer),
                        'distance_km' => $distance,
                        'distance_label' => $distance !== null ? number_format($distance, 1, ',', ' ') . ' km' : null,
                    ];
                });
            })
            ->values();

        if ($filter === 'producers') {
            $vendors = $vendors->where('vendor_role', UserRole::Producer->value)->values();
        } elseif ($filter === 'distributors') {
            $vendors = $vendors->where('vendor_role', UserRole::Distributor->value)->values();
        } elseif ($filter === 'fields') {
            $vendors = $vendors->where('kind', 'champ')->values();
        } elseif ($filter === 'boutiques') {
            $vendors = $vendors->whereIn('kind', ['boutique', 'depot', 'magasin'])->values();
        }

        if ($radius !== null && $viewerCoordinates) {
            $vendors = $vendors->filter(fn (array $vendor) => $vendor['distance_km'] !== null && $vendor['distance_km'] <= $radius)->values();
        }

        $mapCenter = $vendors->first()['coordinates'] ?? [
            'latitude' => $viewerCoordinates[0] ?? 14.7167,
            'longitude' => $viewerCoordinates[1] ?? -17.4677,
        ];

        $layout = $viewer?->isSuperAdmin() ? 'layouts.super-admin' : 'layouts.dashboard';

        return view('map.agricole', [
            'layout' => $layout,
            'vendors' => $vendors,
            'mapCenter' => $mapCenter,
            'currentFilter' => $filter,
            'currentRadius' => $radius,
            'viewerHasLocation' => (bool) $viewerLocation,
            'regionStats' => UserLocation::query()
                ->whereNotNull('region')
                ->select('region')
                ->selectRaw('count(*) as total')
                ->groupBy('region')
                ->orderByDesc('total')
                ->limit(8)
                ->get(),
        ]);
    }
}