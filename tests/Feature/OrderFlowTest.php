<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_checkout_page_redirects_guest()
    {
        $response = $this->get('/checkout');
        $response->assertStatus(302);
    }

    public function test_order_confirmed_page()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/checkout/order-confirmed');
        $response->assertStatus(200);
    }

    public function test_track_order_page()
    {
        $response = $this->get('/track-your-order');
        $response->assertStatus(200);
    }

    public function test_invoice_download_requires_auth()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->get("/invoice/{$order->id}");
        $response->assertStatus(302);
    }

    public function test_coupon_page()
    {
        $response = $this->get('/coupons');
        $response->assertStatus(200);
    }

    public function test_compare_page()
    {
        $response = $this->get('/compare');
        $response->assertStatus(200);
    }

    public function test_wallet_requires_auth()
    {
        $response = $this->get('/wallet');
        $response->assertStatus(302);
    }

    public function test_wishlist_requires_auth()
    {
        $response = $this->get('/wishlists');
        $response->assertStatus(302);
    }

    public function test_purchase_history_requires_auth()
    {
        $response = $this->get('/purchase_history');
        $response->assertStatus(302);
    }
}
