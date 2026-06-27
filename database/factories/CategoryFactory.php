<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'slug' => $this->faker->unique()->slug(1),
            'parent_id' => 0,
            'level' => 0,
            'digital' => 0,
            'banner' => null,
            'icon' => null,
            'cover_image' => null,
            'featured' => 0,
            'commission' => null,
            'commission_type' => null,
            'order_level' => 0,
            'meta_title' => null,
            'meta_description' => null,
        ];
    }

    public function child(int $parentId, int $level = 1)
    {
        return $this->state([
            'parent_id' => $parentId,
            'level' => $level,
        ]);
    }
}
