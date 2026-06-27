<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'code' => $this->faker->unique()->randomNumber(8),
            'grand_total' => $this->faker->randomFloat(2, 50, 2000),
            'payment_status' => 'paid',
            'delivery_status' => 'pending',
            'payment_type' => 'cash_on_delivery',
        ];
    }
}
