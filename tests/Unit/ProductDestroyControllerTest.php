<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Mockery;
use Illuminate\Http\JsonResponse;
use Services\ProductService;
use App\Http\Controllers\Api\ProductController;

class ProductDestroyControllerTest extends TestCase
{
    public function test_destroy_deletes_product_and_returns_json_response()
    {
        $code = '12345678';

        $mockService = Mockery::mock(ProductService::class);
        $mockService->shouldReceive('deleteProduct')
                    ->once()
                    ->with($code)
                    ->andReturnNull();

        $controller = new ProductController($mockService);

        $response = $controller->destroy($code);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals([
            'message' => 'Produto deletado com sucesso'
        ], $response->getData(true));
    }

    public function test_destroy_throws_exception_when_service_fails()
    {
        $code = 'invalid-code';

        $mockService = Mockery::mock(ProductService::class);
        $mockService->shouldReceive('deleteProduct')
                    ->once()
                    ->with($code)
                    ->andThrow(new \Exception('Erro ao deletar'));

        $controller = new ProductController($mockService);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Erro ao deletar');

        $controller->destroy($code);
    }
}
