<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'owner_id' => User::factory(),
            'quantity' => 1,
            'price' => $this->faker->randomFloat(2, 10, 500),
            'tax' => 0,
            'shipping_cost' => 0,
            'discount' => 0,
        ];
    }
}
