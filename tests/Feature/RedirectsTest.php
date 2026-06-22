<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Tests\TestCase;

class RedirectsTest extends TestCase
{
    public function test_legacy_get_routes_redirect_to_french_routes(): void
    {
        $this->get('/home')->assertRedirect('/');
        $this->get('/login')->assertRedirect('/connexion');
        $this->get('/register')->assertRedirect('/inscription');
        $this->get('/forgot-password')->assertRedirect('/mot-de-passe-oublie');
        $this->get('/two-factor-challenge')->assertRedirect('/double-authentification');
        $this->get('/map')->assertRedirect('/carte-agricole');
        $this->get('/locations')->assertRedirect('/mes-localisations');
        $this->get('/products')->assertRedirect('/mes-produits');
    }

    public function test_legacy_password_reset_get_route_redirects_to_current_route(): void
    {
        $token = 'abc123';

        $this->get('/password/reset/'.$token)
            ->assertRedirect('/reinitialiser-mot-de-passe/'.$token);
    }

    public function test_legacy_post_auth_routes_are_handled(): void
    {
        $this->post('/login')->assertStatus(302);
        $this->post('/forgot-password')->assertStatus(302);
        $this->post('/two-factor-challenge')->assertStatus(302);
        $this->post('/reset-password')->assertStatus(302);
    }

    public function test_dashboard_redirects_authenticated_user_to_role_home(): void
    {
        $user = User::factory()->make([
            'role' => UserRole::Producer,
            'status' => UserStatus::Active,
            'archived_at' => null,
        ]);

        $this->be($user);

        $this->get('/dashboard')->assertRedirect('/producteur/dashboard');
    }
}
