<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductShowControllerTest extends TestCase
{
    // use RefreshDatabase;

    public function test_authenticated_user_can_view_a_product_by_code()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $product = Product::factory()->create();
        $response = $this->getJson("/api/products/{$product->code}");
        $response->assertStatus(200);
    }
}
