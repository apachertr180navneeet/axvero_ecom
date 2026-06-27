<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductStockFactory extends Factory
{
    protected $model = ProductStock::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'variant' => '',
            'price' => $this->faker->randomFloat(2, 10, 500),
            'sku' => $this->faker->unique()->ean8,
            'qty' => $this->faker->numberBetween(0, 100),
            'image' => null,
        ];
    }
}
