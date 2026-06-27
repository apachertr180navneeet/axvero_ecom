<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_creation()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'user_type' => 'customer',
        ]);

        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('customer', $user->user_type);
    }

    public function test_admin_user()
    {
        $admin = User::factory()->admin()->create();
        $this->assertEquals('admin', $admin->user_type);
    }

    public function test_seller_user()
    {
        $seller = User::factory()->seller()->create();
        $this->assertEquals('seller', $seller->user_type);
    }

    public function test_user_has_products()
    {
        $user = User::factory()->create();
        $product = \App\Models\Product::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->products);
        $this->assertEquals($product->id, $user->products->first()->id);
    }

    public function test_user_has_orders()
    {
        $user = User::factory()->create();
        $order = \App\Models\Order::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->orders);
        $this->assertEquals($order->id, $user->orders->first()->id);
    }

    public function test_user_has_carts()
    {
        $user = User::factory()->create();
        $cart = \App\Models\Cart::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->carts);
        $this->assertEquals($cart->id, $user->carts->first()->id);
    }

    public function test_user_has_wishlists()
    {
        $user = User::factory()->create();
        $product = \App\Models\Product::factory()->create();
        $wishlist = \App\Models\Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertCount(1, $user->wishlists);
        $this->assertEquals($product->id, $user->wishlists->first()->product_id);
    }

    public function test_user_has_reviews()
    {
        $user = User::factory()->create();
        $product = \App\Models\Product::factory()->create();
        $review = \App\Models\Review::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertCount(1, $user->reviews);
        $this->assertEquals($review->id, $user->reviews->first()->id);
    }

    public function test_user_has_addresses()
    {
        $user = User::factory()->create();
        $address = \App\Models\Address::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->addresses);
        $this->assertEquals($address->id, $user->addresses->first()->id);
    }
}
