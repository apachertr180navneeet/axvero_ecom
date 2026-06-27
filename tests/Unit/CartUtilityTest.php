<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductTax;
use App\Models\ProductStock;
use App\Utility\CartUtility;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CartUtilityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_cart_variant_with_color()
    {
        $product = Product::factory()->create([
            'choice_options' => json_encode([]),
        ]);
        $request = ['color' => '#ff0000'];
        $result = CartUtility::create_cart_variant($product, $request);
        $this->assertEquals('#ff0000', $result);
    }

    public function test_create_cart_variant_with_color_and_choices()
    {
        $product = Product::factory()->create([
            'choice_options' => json_encode([
                ['attribute_id' => 1, 'name' => 'Size'],
                ['attribute_id' => 2, 'name' => 'Material'],
            ]),
        ]);
        $request = [
            'color' => 'Red',
            'attribute_id_1' => 'Large',
            'attribute_id_2' => 'Cotton',
        ];
        $result = CartUtility::create_cart_variant($product, $request);
        $this->assertEquals('Red-Large-Cotton', $result);
    }

    public function test_create_cart_variant_with_choices_only()
    {
        $product = Product::factory()->create([
            'choice_options' => json_encode([
                ['attribute_id' => 1, 'name' => 'Size'],
            ]),
        ]);
        $request = ['attribute_id_1' => 'Small'];
        $result = CartUtility::create_cart_variant($product, $request);
        $this->assertEquals('Small', $result);
    }

    public function test_discount_calculation_percent()
    {
        $product = Product::factory()->create([
            'discount' => 10,
            'discount_type' => 'percent',
            'discount_start_date' => strtotime('-1 day'),
            'discount_end_date' => strtotime('+1 day'),
        ]);
        $result = CartUtility::discount_calculation($product, 100);
        $this->assertEquals(90, $result);
    }

    public function test_discount_calculation_amount()
    {
        $product = Product::factory()->create([
            'discount' => 15,
            'discount_type' => 'amount',
            'discount_start_date' => strtotime('-1 day'),
            'discount_end_date' => strtotime('+1 day'),
        ]);
        $result = CartUtility::discount_calculation($product, 100);
        $this->assertEquals(85, $result);
    }

    public function test_discount_calculation_expired()
    {
        $product = Product::factory()->create([
            'discount' => 10,
            'discount_type' => 'percent',
            'discount_start_date' => strtotime('-5 days'),
            'discount_end_date' => strtotime('-2 days'),
        ]);
        $result = CartUtility::discount_calculation($product, 100);
        $this->assertEquals(100, $result);
    }

    public function test_discount_calculation_no_dates()
    {
        $product = Product::factory()->create([
            'discount' => 10,
            'discount_type' => 'percent',
            'discount_start_date' => null,
            'discount_end_date' => null,
        ]);
        $result = CartUtility::discount_calculation($product, 100);
        $this->assertEquals(90, $result);
    }

    public function test_tax_calculation_percent()
    {
        $product = Product::factory()->create();
        ProductTax::factory()->create([
            'product_id' => $product->id,
            'tax' => 10,
            'tax_type' => 'percent',
        ]);
        $result = CartUtility::tax_calculation($product, 100);
        $this->assertEquals(10, $result);
    }

    public function test_tax_calculation_amount()
    {
        $product = Product::factory()->create();
        ProductTax::factory()->create([
            'product_id' => $product->id,
            'tax' => 20,
            'tax_type' => 'amount',
        ]);
        $result = CartUtility::tax_calculation($product, 100);
        $this->assertEquals(20, $result);
    }

    public function test_tax_calculation_multiple()
    {
        $product = Product::factory()->create();
        ProductTax::factory()->create([
            'product_id' => $product->id,
            'tax' => 10,
            'tax_type' => 'percent',
        ]);
        ProductTax::factory()->create([
            'product_id' => $product->id,
            'tax' => 5,
            'tax_type' => 'amount',
        ]);
        $result = CartUtility::tax_calculation($product, 200);
        $this->assertEquals(25, $result);
    }

    public function test_save_cart_data()
    {
        $product = Product::factory()->create(['user_id' => 1]);
        $cart = new Cart();
        CartUtility::save_cart_data($cart, $product, 99.99, 10.0, 2);

        $this->assertEquals(2, $cart->quantity);
        $this->assertEquals($product->id, $cart->product_id);
        $this->assertEquals($product->user_id, $cart->owner_id);
        $this->assertEquals(99.99, $cart->price);
        $this->assertEquals(10.0, $cart->tax);
    }

    public function test_check_auction_in_cart_returns_true()
    {
        $auctionProduct = Product::factory()->create(['auction_product' => 1]);
        $normalProduct = Product::factory()->create(['auction_product' => 0]);

        $cart1 = Cart::factory()->create(['product_id' => $normalProduct->id]);
        $cart2 = Cart::factory()->create(['product_id' => $auctionProduct->id]);

        $carts = Cart::whereIn('id', [$cart1->id, $cart2->id])->get();
        $result = CartUtility::check_auction_in_cart($carts);
        $this->assertTrue($result);
    }

    public function test_check_auction_in_cart_returns_false()
    {
        $product1 = Product::factory()->create(['auction_product' => 0]);
        $product2 = Product::factory()->create(['auction_product' => 0]);

        $cart1 = Cart::factory()->create(['product_id' => $product1->id]);
        $cart2 = Cart::factory()->create(['product_id' => $product2->id]);

        $carts = Cart::whereIn('id', [$cart1->id, $cart2->id])->get();
        $result = CartUtility::check_auction_in_cart($carts);
        $this->assertFalse($result);
    }

    public function test_get_price_returns_product_stock_price()
    {
        $product = Product::factory()->create([
            'auction_product' => 0,
            'wholesale_product' => 0,
        ]);
        $stock = ProductStock::factory()->create([
            'product_id' => $product->id,
            'price' => 50.00,
        ]);
        $price = CartUtility::get_price($product, $stock, 1);
        $this->assertEquals(50.00, $price);
    }
}
