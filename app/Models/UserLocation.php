<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'region',
        'kind',
        'latitude',
        'longitude',
        'is_primary',
        'publicly_visible',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'is_primary' => 'boolean',
            'publicly_visible' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'user_location_id');
    }

    public function kindLabel(): string
    {
        return match ($this->kind) {
            'champ' => 'Champ',
            'magasin' => 'Magasin',
            'boutique' => 'Boutique',
            'depot' => 'Dépôt',
            'maison' => 'Maison',
            'travail' => 'Travail',
            'ferme' => 'Ferme',
            default => ucfirst($this->kind ?? 'Autre'),
        };
    }

    public function publicLatitude(): float
    {
        return round((float) $this->latitude, 2);
    }

    public function publicLongitude(): float
    {
        return round((float) $this->longitude, 2);
    }

    public function exactCoordinatesVisibleTo(User $viewer): bool
    {
        return $viewer->isAdmin() || $viewer->isSuperAdmin() || $viewer->id === $this->user_id;
    }

    public function coordinatesFor(User $viewer): array
    {
        if ($this->exactCoordinatesVisibleTo($viewer)) {
            return [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'precision' => 'exact',
            ];
        }

        return [
            'latitude' => $this->publicLatitude(),
            'longitude' => $this->publicLongitude(),
            'precision' => 'approximate',
        ];
    }

    public function distanceTo(float $latitude, float $longitude): float
    {
        $earthRadius = 6371;

        $deltaLatitude = deg2rad($latitude - $this->latitude);
        $deltaLongitude = deg2rad($longitude - $this->longitude);

        $a = sin($deltaLatitude / 2) ** 2
            + cos(deg2rad($this->latitude)) * cos(deg2rad($latitude)) * sin($deltaLongitude / 2) ** 2;

        return 2 * $earthRadius * asin(min(1, sqrt($a)));
    }
}