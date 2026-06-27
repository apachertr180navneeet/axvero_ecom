<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductTax;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductTaxFactory extends Factory
{
    protected $model = ProductTax::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'tax_id' => 1,
            'tax' => $this->faker->randomFloat(2, 1, 20),
            'tax_type' => 'percent',
        ];
    }
}
