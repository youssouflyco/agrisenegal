<?php

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'actif';
    case Suspended = 'bloqué';
    case Archived = 'archivé';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Actif',
            self::Suspended => 'Bloqué',
            self::Archived => 'Archivé',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Active => 'bg-emerald-100 text-emerald-700',
            self::Suspended => 'bg-orange-100 text-orange-700',
            self::Archived => 'bg-soft-gray text-gray-600',
        };
    }
}
