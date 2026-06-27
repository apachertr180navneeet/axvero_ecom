<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CartTest extends TestCase
{
    use DatabaseTransactions;

    public function test_cart_page_loads()
    {
        $response = $this->get('/cart');
        $response->assertStatus(200);
    }

    public function test_add_to_cart_as_guest()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'unit_price' => 100,
            'published' => 1,
            'approved' => 1,
        ]);
        ProductStock::factory()->create([
            'product_id' => $product->id,
            'price' => 100,
            'qty' => 10,
        ]);

        $response = $this->post('/cart/addtocart', [
            'id' => $product->id,
            'quantity' => 1,
        ]);

        $this->assertDatabaseHas('carts', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    public function test_add_to_cart_as_authenticated_user()
    {
        $user = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'user_id' => $admin->id,
            'unit_price' => 50,
            'published' => 1,
            'approved' => 1,
        ]);
        ProductStock::factory()->create([
            'product_id' => $product->id,
            'price' => 50,
            'qty' => 10,
        ]);

        $response = $this->actingAs($user)->post('/cart/addtocart', [
            'id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('carts', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'quantity' => 2,
        ]);
    }
}
