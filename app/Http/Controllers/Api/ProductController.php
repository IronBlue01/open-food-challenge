<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Services\ProductCollection;
use Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Lista os produtos",
     *     security={{"sanctum":{}}},
     *     description="Retorna uma lista paginada de produtos",
     *     operationId="listProducts",
     *     tags={"Produtos"},
     * 
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página para paginação",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos retornada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             ),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string"),
     *                 @OA\Property(property="last", type="string"),
     *                 @OA\Property(property="prev", type="string", nullable=true),
     *                 @OA\Property(property="next", type="string", nullable=true),
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): AnonymousResourceCollection 
    {
        return ProductResource::collection($this->productService->listProduct());
    }

    /**
     * @OA\Get(
     *     path="/api/products/{code}",
     *     summary="Detalhes de um produto",
     *     security={{"sanctum": {}}},
     *     tags={"Produtos"},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         description="Código do produto",
     *         required=true,
     *         @OA\Schema(type="string", example="ABC123")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="code", type="string", example="ABC123"),
     *                 @OA\Property(property="name", type="string", example="Arroz Tio João 5kg"),
     *                 @OA\Property(property="price", type="number", format="float", example=25.99),
     *                 @OA\Property(property="stock", type="integer", example=100)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado"
     *     )
     * )
     */
    public function show(string $code): ProductResource
    {
        return ProductResource::make($this->productService->detailProduct($code));
    }

    /**
     * Atualiza um produto pelo código.
     *
     * Atualiza os dados de um produto existente com base no código fornecido.
     *
     * @OA\Put(
     *     path="/products/{code}",
     *     summary="Atualiza um produto",
     *     description="Atualiza um produto existente com os dados enviados.",
     *     operationId="updateProduct",
     *     tags={"Produtos"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         description="Código do produto",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code", "status", "imported_t", "url", "creator", "created_t", "last_modified_t", "product_name"},
     *             @OA\Property(property="code", type="integer", example=20221126),
     *             @OA\Property(property="status", type="string", enum={"published", "draft", "trash"}, example="published"),
     *             @OA\Property(property="imported_t", type="string", format="date-time", example="2020-02-07T16:00:00Z"),
     *             @OA\Property(property="url", type="string", format="url", example="https://world.openfoodfacts.org/product/20221126"),
     *             @OA\Property(property="creator", type="string", example="securita"),
     *             @OA\Property(property="created_t", type="integer", example=1415302075),
     *             @OA\Property(property="last_modified_t", type="integer", example=1572265837),
     *             @OA\Property(property="product_name", type="string", example="Madalenas quadradas"),
     *             @OA\Property(property="quantity", type="string", example="380 g (6 x 2 u.)"),
     *             @OA\Property(property="brands", type="string", example="La Cestera"),
     *             @OA\Property(property="categories", type="string", example="Bolos, Lanches doces"),
     *             @OA\Property(property="labels", type="string", example="Contem gluten, Contém ovos"),
     *             @OA\Property(property="cities", type="string", example=""),
     *             @OA\Property(property="purchase_places", type="string", example="Braga,Portugal"),
     *             @OA\Property(property="stores", type="string", example="Lidl"),
     *             @OA\Property(property="ingredients_text", type="string", example="farinha de trigo, açúcar, óleo vegetal"),
     *             @OA\Property(property="traces", type="string", example="Leite, Soja"),
     *             @OA\Property(property="serving_size", type="string", example="madalena 31.7 g"),
     *             @OA\Property(property="serving_quantity", type="number", format="float", example=31.7),
     *             @OA\Property(property="nutriscore_score", type="integer", example=17),
     *             @OA\Property(property="nutriscore_grade", type="string", enum={"a", "b", "c", "d", "e"}, example="d"),
     *             @OA\Property(property="main_category", type="string", example="en:madeleines"),
     *             @OA\Property(property="image_url", type="string", format="url", example="https://static.openfoodfacts.org/image.jpg")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Produto atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=20221126),
     *             @OA\Property(property="status", type="string", example="published"),
     *             @OA\Property(property="product_name", type="string", example="Madalenas quadradas"),
     *             @OA\Property(property="image_url", type="string", format="url", example="https://static.openfoodfacts.org/image.jpg"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-22T15:03:00Z")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Os dados fornecidos são inválidos."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado"
     *     )
     * )
     */

    public function update(string $code, ProductRequest $request): ProductResource
    {
        return ProductResource::make($this->productService->updateProduct($code, $request->validated()));
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{code}",
     *     summary="Deleta um produto",
     *     tags={"Produtos"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         description="Código do produto",
     *         required=true,
     *         @OA\Schema(type="string", example="ABC123")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto deletado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Produto deletado com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado"
     *     )
     * )
     */
    public function destroy(string $code): JsonResponse
    {
        $this->productService->deleteProduct($code);
        return response()->json([
            'message' => 'Produto deletado com sucesso'
        ]);
    }
}
