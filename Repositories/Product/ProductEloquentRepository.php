<?php

namespace Repositories\Product;

use App\Enum\ProductStatusEnum;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductEloquentRepository implements ProductRepository
{
    public function __construct(
        public readonly Product $model
    ) {
    }

    public function storeOrUpdate(array $data, array $collums): void
    {
        DB::table('products')->upsert($data, ['code'], $collums);
    }

    public function listProduct(): LengthAwarePaginator
    {
        return $this->model
                    ->whereNotIn('status', [ProductStatusEnum::TRASH->descricao()])
                    ->paginate();
    }

    public function detailProduct(string $code): Product
    {
        return $this->model
                    ->where('code', $code)
                    ->whereNotIn('status', [ProductStatusEnum::TRASH->descricao()])
                    ->firstOrFail();
    }

    public function deleteProduct(string $code): void
    {
        $product = $this->detailProduct($code);
        $product->update(['status' => ProductStatusEnum::TRASH->descricao()]);
    }
}
