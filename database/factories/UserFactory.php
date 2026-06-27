<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
            'remember_token' => Str::random(10),
            'user_type' => 'customer',
        ];
    }

    public function admin()
    {
        return $this->state(['user_type' => 'admin']);
    }

    public function seller()
    {
        return $this->state(['user_type' => 'seller']);
    }

    public function unverified()
    {
        return $this->state(['email_verified_at' => null]);
    }
}
