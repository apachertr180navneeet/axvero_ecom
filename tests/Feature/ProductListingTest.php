<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductListingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_product_listing_by_category()
    {
        $user = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'published' => 1,
            'approved' => 1,
        ]);
        $product->categories()->attach($category->id);

        $response = $this->get("/category/{$category->slug}");
        $response->assertStatus(200);
    }

    public function test_variant_price_endpoint()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'unit_price' => 100,
            'published' => 1,
            'approved' => 1,
        ]);
        $stock = ProductStock::factory()->create([
            'product_id' => $product->id,
            'variant' => 'Red-Large',
            'price' => 120,
            'qty' => 10,
        ]);

        $response = $this->post('/product/variant-price', [
            'id' => $product->id,
        ]);
        $response->assertStatus(200);
    }

    public function test_ajax_search()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'name' => 'SearchableTestProduct',
            'published' => 1,
            'approved' => 1,
        ]);

        $response = $this->post('/ajax-search', [
            'search' => 'SearchableTest',
        ]);
        $response->assertStatus(200);
    }

    public function test_brand_page()
    {
        $brand = Brand::factory()->create();
        $response = $this->get("/brand/{$brand->slug}");
        $response->assertStatus(200);
    }
}
