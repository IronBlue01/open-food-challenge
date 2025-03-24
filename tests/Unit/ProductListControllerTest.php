<?php 
namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\Api\ProductController;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Services\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mockery;

class ProductListControllerTest extends TestCase
{
    public function test_index_returns_paginated_products()
    {
    $productsData = Product::factory()->count(2)->make();

    $paginator = new LengthAwarePaginator(
        $productsData,
        $productsData->count(),
        15,
        1
    );

    $productServiceMock = \Mockery::mock(ProductService::class);
    
    $productServiceMock
        ->shouldReceive('listProduct')
        ->once()
        ->andReturn($paginator);

    $controller = new ProductController($productServiceMock);
    $response = $controller->index();

    $this->assertInstanceOf(AnonymousResourceCollection::class, $response);

    $this->assertEquals(
        ProductResource::collection($paginator)->resolve(),
        $response->resolve()
    );
    }

    public function test_index_returns_empty_collection()
    {
        $paginator = new LengthAwarePaginator(
            collect([]),
            0,
            15,
            1
        );

        $mock = \Mockery::mock(ProductService::class);
        $mock->shouldReceive('listProduct')->once()->andReturn($paginator);

        $controller = new \App\Http\Controllers\Api\ProductController($mock);
        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertEmpty($response->resolve());
    }

    public function test_index_throws_exception_when_service_fails()
    {
        $mock = \Mockery::mock(ProductService::class);
        $mock->shouldReceive('listProduct')->once()->andThrow(new \Exception('Erro'));

        $controller = new \App\Http\Controllers\Api\ProductController($mock);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Erro');

        $controller->index();
    }
}
