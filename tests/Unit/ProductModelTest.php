<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductModelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_product_belongs_to_user()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $product->user);
        $this->assertEquals($user->id, $product->user->id);
    }

    public function test_product_belongs_to_main_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->main_category);
        $this->assertEquals($category->id, $product->main_category->id);
    }

    public function test_product_belongs_to_many_categories()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $product = Product::factory()->create();
        $product->categories()->attach([$category1->id, $category2->id]);

        $this->assertCount(2, $product->categories);
    }

    public function test_digital_product_scope()
    {
        $digital = Product::factory()->digital()->create();
        $physical = Product::factory()->create(['digital' => 0]);

        $products = Product::where('digital', 1)->get();
        $this->assertTrue($products->contains($digital));
        $this->assertFalse($products->contains($physical));
    }

    public function test_auction_product_scope()
    {
        $auction = Product::factory()->auction()->create();
        $normal = Product::factory()->create(['auction_product' => 0]);

        $products = Product::where('auction_product', 1)->get();
        $this->assertTrue($products->contains($auction));
        $this->assertFalse($products->contains($normal));
    }

    public function test_published_scope()
    {
        $published = Product::factory()->create(['published' => 1]);
        $unpublished = Product::factory()->unpublished()->create();

        $products = Product::where('published', 1)->get();
        $this->assertTrue($products->contains($published));
        $this->assertFalse($products->contains($unpublished));
    }

    public function test_product_has_stocks()
    {
        $product = Product::factory()->create();
        $stock1 = \App\Models\ProductStock::factory()->create(['product_id' => $product->id]);
        $stock2 = \App\Models\ProductStock::factory()->create(['product_id' => $product->id]);

        $this->assertCount(2, $product->stocks);
    }

    public function test_product_has_taxes()
    {
        $product = Product::factory()->create();
        $tax = \App\Models\ProductTax::factory()->create(['product_id' => $product->id]);

        $this->assertCount(1, $product->taxes);
        $this->assertEquals($tax->id, $product->taxes->first()->id);
    }
}
