<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Console\Command;

class ResetSuperAdminPassword extends Command
{
    protected $signature = 'agri:reset-super-admin {--password=AgriSenegal2026!}';

    protected $description = 'Réinitialise le mot de passe du Super Administrateur';

    public function handle(): int
    {
        $password = $this->option('password');

        $user = User::where('role', UserRole::SuperAdmin)->first();

        if (! $user) {
            $this->error('Aucun Super Administrateur trouvé. Lancez : php artisan db:seed --class=SuperAdminSeeder');

            return self::FAILURE;
        }

        $user->update([
            'password' => $password,
            'status' => UserStatus::Active,
            'suspended_at' => null,
            'archived_at' => null,
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ]);

        $this->info('Super Admin réinitialisé avec succès.');
        $this->line("Email : {$user->email}");
        $this->line("Mot de passe : {$password}");

        return self::SUCCESS;
    }
}
