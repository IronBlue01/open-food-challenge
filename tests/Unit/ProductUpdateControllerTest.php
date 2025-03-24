<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Mockery;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use Services\ProductService;
use App\Http\Controllers\Api\ProductController;

class ProductUpdateControllerTest extends TestCase
{
    use WithFaker;

    public function test_update_returns_product_resource()
    {
        $code = '12345678';
        $validatedData = [
            'status' => 'published',
            'product_name' => 'Novo nome',
        ];

        $updatedProduct = Product::factory()->make(array_merge($validatedData, ['code' => $code]));

        $requestMock = Mockery::mock(ProductRequest::class);
        $requestMock->shouldReceive('validated')
                    ->once()
                    ->andReturn($validatedData);

        $productServiceMock = Mockery::mock(ProductService::class);
        $productServiceMock->shouldReceive('updateProduct')
                           ->once()
                           ->with($code, $validatedData)
                           ->andReturn($updatedProduct);

        $controller = new ProductController($productServiceMock);

        $response = $controller->update($code, $requestMock);

        $this->assertInstanceOf(ProductResource::class, $response);
        $this->assertEquals(
            ProductResource::make($updatedProduct)->resolve(),
            $response->resolve()
        );
    }

    public function test_update_throws_exception_when_service_fails()
    {
        $code = 'invalid';
        $requestMock = Mockery::mock(ProductRequest::class);
        $requestMock->shouldReceive('validated')->once()->andReturn([]);

        $productServiceMock = Mockery::mock(ProductService::class);
        $productServiceMock->shouldReceive('updateProduct')
                           ->once()
                           ->with($code, [])
                           ->andThrow(new \Exception('Produto não encontrado'));

        $controller = new ProductController($productServiceMock);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Produto não encontrado');

        $controller->update($code, $requestMock);
    }
}
