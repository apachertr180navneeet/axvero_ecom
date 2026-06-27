<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug,
            'added_by' => 'admin',
            'user_id' => User::factory(),
            'category_id' => 0,
            'unit_price' => $this->faker->randomFloat(2, 10, 1000),
            'unit' => 'pcs',
            'current_stock' => $this->faker->numberBetween(0, 100),
            'description' => $this->faker->paragraph,
            'published' => 1,
            'approved' => 1,
            'tags' => 'test,product',
            'choice_options' => json_encode([]),
            'colors' => json_encode([]),
            'attributes' => json_encode([]),
            'featured' => 0,
            'auction_product' => 0,
            'digital' => 0,
            'wholesale_product' => 0,
            'discount' => 0.00,
            'discount_type' => 'amount',
            'tax' => 0,
            'tax_type' => 'amount',
            'shipping_cost' => 0,
            'num_of_sale' => 0,
            'rating' => 0.00,
            'meta_title' => null,
            'meta_description' => null,
        ];
    }

    public function digital()
    {
        return $this->state(['digital' => 1]);
    }

    public function auction()
    {
        return $this->state(['auction_product' => 1]);
    }

    public function wholesale()
    {
        return $this->state(['wholesale_product' => 1]);
    }

    public function unpublished()
    {
        return $this->state(['published' => 0]);
    }
}
