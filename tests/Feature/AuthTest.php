<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_page_loads()
    {
        $response = $this->get('/users/login');
        $response->assertStatus(200);
    }

    public function test_registration_page_loads()
    {
        $response = $this->get('/users/registration');
        $response->assertStatus(200);
    }

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'user_type' => 'customer',
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'logintest@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'logintest@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    public function test_login_fails_with_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'wrongpass@example.com',
            'password' => Hash::make('correct_password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'wrongpass@example.com',
            'password' => 'wrong_password',
        ]);

        $this->assertGuest();
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302);
        $response->assertRedirectContains('login');
    }

    public function test_logout_works()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->get('/logout');
        $this->assertGuest();
    }

    public function test_seller_login_page_loads()
    {
        $response = $this->get('/seller/login');
        $response->assertStatus(200);
    }

    public function test_password_reset_page_loads()
    {
        $response = $this->get('/password/reset');
        $response->assertStatus(200);
    }
}
