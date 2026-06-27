<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductTax;
use App\Models\User;
use App\Services\ProductService;
use App\Services\ProductStockService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_store_creates_product()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $service = new ProductService();
        $data = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'unit_price' => '100.00',
            'unit' => 'pcs',
            'current_stock' => 10,
            'description' => 'Test description',
            'tags' => [json_encode([['value' => 'tag1'], ['value' => 'tag2']])],
            'shipping_type' => 'free',
            'button' => 'publish',
            'colors_active' => false,
            'product_type' => 'admin',
        ];

        $product = $service->store($data);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(1, $product->published);
        $this->assertEquals(1, $product->approved);
    }

    public function test_store_creates_unpublished_product()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $service = new ProductService();
        $data = [
            'name' => 'Draft Product',
            'slug' => 'draft-product',
            'unit_price' => '50.00',
            'unit' => 'pcs',
            'current_stock' => 5,
            'description' => 'Draft',
            'tags' => [json_encode([['value' => 'draft']])],
            'shipping_type' => 'free',
            'button' => 'draft',
            'colors_active' => false,
            'product_type' => 'admin',
        ];

        $product = $service->store($data);
        $this->assertEquals(0, $product->published);
    }

    public function test_product_search_by_category()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $product = Product::factory()->create([
            'user_id' => $user->id,
            'added_by' => 'admin',
            'published' => 1,
            'approved' => 1,
            'digital' => 0,
            'wholesale_product' => 0,
            'auction_product' => 0,
        ]);

        $service = new ProductService();
        $results = $service->products_search([
            'product_type' => 'physical',
            'category' => null,
            'product_id' => null,
            'search_key' => $product->name,
        ]);

        $this->assertTrue($results->contains($product));
    }

    public function test_product_search_by_keyword()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        Product::factory()->create([
            'name' => 'UniqueProductXYZ',
            'user_id' => $user->id,
            'added_by' => 'admin',
            'published' => 1,
            'approved' => 1,
            'digital' => 0,
            'wholesale_product' => 0,
            'auction_product' => 0,
        ]);

        $service = new ProductService();
        $results = $service->product_search([
            'product_type' => 'physical',
            'category' => null,
            'product_id' => null,
            'search_key' => 'UniqueProductXYZ',
        ]);

        $this->assertCount(1, $results);
    }

    public function test_stock_service_creates_variants()
    {
        $product = Product::factory()->create();
        $service = new ProductStockService();

        $data = [
            'colors_active' => false,
            'current_stock' => 25,
            'unit_price' => 99.99,
        ];

        $service->store($data, $product);

        $product->refresh();
        $stock = ProductStock::where('product_id', $product->id)->first();
        $this->assertNotNull($stock);
        $this->assertEquals(0, $product->variant_product);
        $this->assertEquals(99.99, $stock->price);
        $this->assertEquals(25, $stock->qty);
    }

    public function test_tax_service_stores_taxes()
    {
        $product = Product::factory()->create();
        $service = new ProductTaxService();

        $data = [
            'product_id' => $product->id,
            'tax_id' => [1, 2],
            'tax' => [10, 5],
            'tax_type' => ['percent', 'amount'],
        ];

        $service->store($data);
        $this->assertCount(2, ProductTax::where('product_id', $product->id)->get());
    }

    public function test_flash_deal_service_stores()
    {
        $product = Product::factory()->create();
        $service = new ProductFlashDealService();

        $flashDeal = \App\Models\FlashDeal::factory()->create();

        $data = [
            'flash_deal_id' => $flashDeal->id,
            'flash_discount' => 20,
            'flash_discount_type' => 'percent',
        ];

        $service->store($data, $product);

        $product->refresh();
        $this->assertEquals(20, $product->discount);
        $this->assertEquals('percent', $product->discount_type);

        $this->assertDatabaseHas('flash_deal_products', [
            'flash_deal_id' => $flashDeal->id,
            'product_id' => $product->id,
        ]);
    }
}
