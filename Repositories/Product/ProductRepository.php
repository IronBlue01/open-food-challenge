<?php

namespace Repositories\Product;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepository
{
    public function storeOrUpdate(array $data, array $collums): void;
    public function listProduct(): LengthAwarePaginator;
    public function detailProduct(string $code): Product;
    public function deleteProduct(string $code): void;
}