<?php

namespace App\Constants;

use App\Enums\UserRole;

class UserRoleConstants
{
    public const ALLOWED_ROLES = [
        'client' => UserRole::Client,
        'producteur' => UserRole::Producer,
        'distributeur' => UserRole::Distributor,
    ];

    // Allowed business roles
    public const BUSINESS_PROFILE_ROLES = [
        'producteur' => UserRole::Producer,
        'distributeur' => UserRole::Distributor,
    ];
}
