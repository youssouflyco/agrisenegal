<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Complaint;
use App\Models\Product;
use App\Models\WithdrawalRequest;
use App\Models\UserLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'photo',
        'password',
        'role',
        'status',
        'two_factor_secret',
        'two_factor_confirmed_at',
        'two_factor_enabled',
        'suspended_at',
        'archived_at',
        'created_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'suspended_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdAdmins(): HasMany
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(AdminActivityLog::class, 'admin_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(UserLocation::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function withdrawalRequests(): HasMany
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    public function primaryLocation(): HasOne
    {
        return $this->hasOne(UserLocation::class)->where('is_primary', true);
    }

    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            return trim("{$this->first_name} {$this->last_name}");
        }

        return $this->name;
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::url($this->photo);
        }

        return asset('images/agri/placeholders/avatar.svg');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isClient(): bool
    {
        return $this->role === UserRole::Client;
    }

    public function isStaff(): bool
    {
        return in_array($this->role, [UserRole::SuperAdmin, UserRole::Admin], true);
    }

    public function canLogin(): bool
    {
        return $this->status === UserStatus::Active && ! $this->archived_at;
    }

    public function homeUrl(): string
    {
        return match ($this->role) {
            UserRole::SuperAdmin => route('super-admin.dashboard'),
            UserRole::Admin => route('admin.dashboard'),
            UserRole::Client => route('client.dashboard'),
            UserRole::Producer => route('producer.dashboard'),
            UserRole::Distributor => route('distributor.dashboard'),
        };
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', UserRole::Admin);
    }

    public function scopeActive($query)
    {
        return $query->where('status', UserStatus::Active);
    }

    public function scopeNotArchived($query)
    {
        return $query->where('status', '!=', UserStatus::Archived);
    }
}
