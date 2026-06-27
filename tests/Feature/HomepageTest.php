<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_homepage_loads()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_homepage_shows_products()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'name' => 'Homepage Test Product',
            'published' => 1,
            'approved' => 1,
        ]);
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_product_detail_page()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'slug' => 'test-product-detail',
            'published' => 1,
            'approved' => 1,
        ]);
        $response = $this->get("/product/{$product->slug}");
        $response->assertStatus(200);
    }

    public function test_all_brands_page()
    {
        $response = $this->get('/brands');
        $response->assertStatus(200);
    }

    public function test_all_categories_page()
    {
        $response = $this->get('/categories');
        $response->assertStatus(200);
    }

    public function test_all_sellers_page()
    {
        $response = $this->get('/sellers');
        $response->assertStatus(200);
    }

    public function test_flash_deals_page()
    {
        $response = $this->get('/flash-deals');
        $response->assertStatus(200);
    }

    public function test_todays_deal_page()
    {
        $response = $this->get('/todays-deal');
        $response->assertStatus(200);
    }

    public function test_best_selling_page()
    {
        $response = $this->get('/best-selling');
        $response->assertStatus(200);
    }

    public function test_featured_products_page()
    {
        $response = $this->get('/featured-products');
        $response->assertStatus(200);
    }

    public function test_inhouse_products_page()
    {
        $response = $this->get('/inhouse');
        $response->assertStatus(200);
    }

    public function test_search_page()
    {
        $response = $this->get('/search');
        $response->assertStatus(200);
    }

    public function test_policies_accessible()
    {
        $this->get('/seller-policy')->assertStatus(200);
        $this->get('/return-policy')->assertStatus(200);
        $this->get('/support-policy')->assertStatus(200);
        $this->get('/terms')->assertStatus(200);
        $this->get('/privacy-policy')->assertStatus(200);
    }

    public function test_blog_page()
    {
        $response = $this->get('/blog');
        $response->assertStatus(200);
    }
}
