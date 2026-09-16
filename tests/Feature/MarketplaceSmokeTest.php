<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_render_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get('/products');
        $response->assertStatus(200);

        $product = Product::first();
        if ($product) {
            $response = $this->get('/products/'.$product->slug);
            $response->assertStatus(200);
        }

        $response = $this->get('/games');
        $response->assertStatus(200);

        $game = Game::first();
        if ($game) {
            $response = $this->get('/games/'.$game->slug);
            $response->assertStatus(200);
        }

        $response = $this->get('/promo');
        $response->assertStatus(200);

        $response = $this->get('/news');
        $response->assertStatus(200);

        $response = $this->get('/faq');
        $response->assertStatus(200);

        $response = $this->get('/about');
        $response->assertStatus(200);

        $response = $this->get('/contact');
        $response->assertStatus(200);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_authenticated_customer_routes(): void
    {
        $user = User::where('role', 'customer')->first();

        $response = $this->actingAs($user)->get('/cart');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/orders');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/wishlist');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/notifications');
        $response->assertStatus(200);

        $product = Product::active()->inStock()->first();
        if ($product) {
            $response = $this->actingAs($user)->post('/checkout/direct', [
                'product_id' => $product->id,
                'quantity' => 1,
                'customer_name' => 'Wahyu Customer',
                'customer_email' => 'customer@gamemarket.id',
                'customer_whatsapp' => '081234567890',
                'payment_method' => 'qris',
            ]);
            $response->assertRedirect();
            $response->assertSessionHasNoErrors();
        }
    }

    public function test_admin_routes(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/products');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/orders');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/games');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/categories');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/payments');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/promos');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/banners');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/news');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/mock-payment');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/audit-logs');
        $response->assertStatus(200);
    }
}
