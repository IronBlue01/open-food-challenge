<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Mockery;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Services\ProductService;
use App\Http\Controllers\Api\ProductController;

class ProductShowControllerTest extends TestCase
{
    public function test_show_returns_product_resource()
    {
        $code = '12345678';

        $product = Product::factory()->make(['code' => $code]);

        $mock = \Mockery::mock(ProductService::class);
        $mock->shouldReceive('detailProduct')
             ->once()
             ->with($code)
             ->andReturn($product);

        $controller = new ProductController($mock);
        $response = $controller->show($code);

        $this->assertInstanceOf(ProductResource::class, $response);

        $this->assertEquals(
            ProductResource::make($product)->resolve(),
            $response->resolve()
        );
    }

    public function test_show_throws_exception_when_product_not_found()
    {
        $code = 'invalid-code';

        $mock = Mockery::mock(ProductService::class);
        $mock->shouldReceive('detailProduct')
             ->once()
             ->with($code)
             ->andThrow(new \Exception('Produto não encontrado'));

        $controller = new ProductController($mock);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Produto não encontrado');

        $controller->show($code);
    }
}
