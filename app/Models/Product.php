<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_location_id',
        'name',
        'description',
        'unit',
        'price',
        'quantity',
        'image_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(UserLocation::class, 'user_location_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ProductPhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image_path
            ? asset('storage/' . $this->image_path)
            : asset('images/agri/placeholders/avatar.svg');
    }

    public function getGalleryImageUrlsAttribute(): array
    {
        $photoUrls = $this->relationLoaded('photos')
            ? $this->photos->map(fn (ProductPhoto $photo) => asset('storage/' . $photo->path))->all()
            : [];

        if ($this->image_path) {
            array_unshift($photoUrls, asset('storage/' . $this->image_path));
        }

        return array_values(array_unique($photoUrls));
    }
}