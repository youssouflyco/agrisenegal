<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $firstName = fake()->randomElement(['Amadou', 'Fatou', 'Ibrahima', 'Awa', 'Moussa', 'Mariama', 'Ousmane', 'Khady']);
        $lastName = fake()->randomElement(['Diallo', 'Sow', 'Ndiaye', 'Ba', 'Fall', 'Gueye', 'Sy', 'Mbaye']);

        return [
            'name' => "{$firstName} {$lastName}",
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+221 7'.fake()->numerify('########'),
            'email_verified_at' => now(),
            'password' => static::$password ??= 'password',
            'role' => UserRole::Client,
            'status' => UserStatus::Active,
            'remember_token' => Str::random(10),
        ];
    }
}
