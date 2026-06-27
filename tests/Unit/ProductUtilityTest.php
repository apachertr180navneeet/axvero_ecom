<?php

namespace Tests\Unit;

use App\Models\Color;
use App\Utility\ProductUtility;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductUtilityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_attribute_options_with_colors()
    {
        $collection = [
            'colors_active' => true,
            'colors' => ['#ff0000', '#00ff00'],
        ];
        $options = ProductUtility::get_attribute_options($collection);
        $this->assertCount(1, $options);
        $this->assertEquals(['#ff0000', '#00ff00'], $options[0]);
    }

    public function test_get_attribute_options_without_colors()
    {
        $collection = [
            'colors_active' => false,
        ];
        $options = ProductUtility::get_attribute_options($collection);
        $this->assertCount(0, $options);
    }

    public function test_get_attribute_options_with_choice_no()
    {
        $collection = [
            'choice_no' => [1, 2],
        ];
        $options = ProductUtility::get_attribute_options($collection);
        $this->assertCount(0, $options);
    }

    public function test_get_combination_string_with_color()
    {
        Color::factory()->create(['code' => '#ff0000', 'name' => 'Red']);
        $combination = ['#ff0000', 'Large'];
        $collection = [
            'colors_active' => true,
            'colors' => ['#ff0000'],
        ];
        $result = ProductUtility::get_combination_string($combination, $collection);
        $this->assertEquals('Red-Large', $result);
    }

    public function test_get_combination_string_without_color()
    {
        $combination = ['Small', 'Cotton'];
        $collection = [
            'colors_active' => false,
        ];
        $result = ProductUtility::get_combination_string($combination, $collection);
        $this->assertEquals('Small-Cotton', $result);
    }

    public function test_get_combination_string_no_attributes()
    {
        $combination = ['Single'];
        $collection = [
            'colors_active' => false,
            'colors' => [],
        ];
        $result = ProductUtility::get_combination_string($combination, $collection);
        $this->assertEquals('Single', $result);
    }
}
