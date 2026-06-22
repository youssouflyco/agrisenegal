<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Client = 'client';
    case Producer = 'producer';
    case Distributor = 'distributor';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Administrateur',
            self::Admin => 'Administrateur',
            self::Client => 'Client',
            self::Producer => 'Producteur',
            self::Distributor => 'Distributeur',
        };
    }
}
