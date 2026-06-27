<?php

namespace Database\Factories;

use App\Models\FlashDeal;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlashDealFactory extends Factory
{
    protected $model = FlashDeal::class;

    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
            'start_date' => strtotime('-1 day'),
            'end_date' => strtotime('+7 days'),
            'status' => 1,
            'featured' => 1,
        ];
    }
}
