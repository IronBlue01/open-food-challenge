<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductListControllerTest extends TestCase
{
    // use RefreshDatabase;
    
    public function test_authenticated_user_can_list_products(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Product::factory()->count(16)->create();

        $response = $this->getJson('/api/products');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'code',
                    'status',
                    'imported_t',
                    'url',
                    'creator',
                    'created_t',
                    'last_modified_t',
                    'product_name',
                    'quantity',
                    'brands',
                    'categories',
                    'labels',
                    'cities',
                    'purchase_places',
                    'stores',
                    'ingredients_text',
                    'traces',
                    'serving_size',
                    'serving_quantity',
                    'nutriscore_score',
                    'nutriscore_grade',
                    'main_category',
                    'image_url',
                ]
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active',
                    ]
                ],
                'path',
                'per_page',
                'to',
                'total'
            ]
        ]);
    }

    public function test_unauthenticated_user_cannot_access_product_list()
    {
        $response = $this->getJson('/api/products');
        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function test_product_list_returns_paginated_response()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Product::factory()->count(30)->create();

        $response = $this->getJson('/api/products?page=2');

        $response->assertStatus(200);
        $response->assertJsonFragment(['current_page' => 2]);
        $this->assertCount(15, $response->json('data')); // se seu paginate() está com 15 por página
    }
}
