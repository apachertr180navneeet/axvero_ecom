<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryTranslationFactory extends Factory
{
    protected $model = CategoryTranslation::class;

    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'lang' => 'en',
            'name' => $this->faker->word,
        ];
    }
}
