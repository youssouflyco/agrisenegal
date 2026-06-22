<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = 'AgriSenegal2026!';

        $superAdmin = User::where('role', UserRole::SuperAdmin)->first();

        if ($superAdmin) {
            $superAdmin->update([
                'name' => 'Youssouf Coly',
                'first_name' => 'Youssouf',
                'last_name' => 'Coly',
                'password' => $password,
                'status' => UserStatus::Active,
                'suspended_at' => null,
                'archived_at' => null,
                'two_factor_enabled' => false,
                'two_factor_secret' => null,
                'two_factor_confirmed_at' => null,
            ]);

            return;
        }

        User::create([
            'name' => 'Youssouf Coly',
            'first_name' => 'Youssouf',
            'last_name' => 'Coly',
            'email' => 'superadmin@agrisenegal.sn',
            'phone' => '+221 77 000 00 00',
            'password' => $password,
            'role' => UserRole::SuperAdmin,
            'status' => UserStatus::Active,
            'email_verified_at' => now(),
        ]);
    }
}
