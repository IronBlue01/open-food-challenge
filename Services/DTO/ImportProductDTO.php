<?php

namespace Services\DTO;
use App\Enum\ProductStatusEnum;

final class ImportProductDTO 
{
    public function __construct(
        public readonly string $code,
        public readonly string $status,
        public readonly string $imported_t,
        public readonly string $url,
        public readonly string $creator,
        public readonly int $created_t,
        public readonly int $last_modified_t,
        public readonly string $product_name,
        public readonly string $quantity,
        public readonly string $brands,
        public readonly string $categories,
        public readonly string $labels,
        public readonly ?string $cities,
        public readonly string $purchase_places,
        public readonly string $stores,
        public readonly string $ingredients_text,
        public readonly ?string $traces,
        public readonly string $serving_size,
        public readonly float $serving_quantity,
        public readonly int $nutriscore_score,
        public readonly string $nutriscore_grade,
        public readonly string $main_category,
        public readonly string $image_url
    ) {}

    public static function makeFromRequest(array $dados): self
    {
        return new self(
            code: $dados['code'],
            status: $dados['status'] ?? ProductStatusEnum::PUBLISH->descricao(),
            imported_t: $dados['imported_t'] = now(),
            url: $dados['url'],
            creator: $dados['creator'],
            created_t: $dados['created_t'],
            last_modified_t: $dados['last_modified_t'],
            product_name: $dados['product_name'],
            quantity: $dados['quantity'],
            brands: $dados['brands'],
            categories: $dados['categories'],
            labels: $dados['labels'],
            cities: $dados['cities'] ?? null,
            purchase_places: $dados['purchase_places'],
            stores: $dados['stores'],
            ingredients_text: $dados['ingredients_text'],
            traces: $dados['traces'] ?? null,
            serving_size: $dados['serving_size'],
            serving_quantity: (float) $dados['serving_quantity'],
            nutriscore_score: (int) $dados['nutriscore_score'] ?? 0,
            nutriscore_grade: $dados['nutriscore_grade'],
            main_category: $dados['main_category'] ?? 'null',
            image_url: $dados['image_url'] ?? 'null',
        );
    }

    public function toDatabase(): array
    {
        return [
            'code' => $this->code,
            'status' => $this->status,
            'imported_t' => $this->imported_t,
            'url' => $this->url,
            'creator' => $this->creator,
            'created_t' => $this->created_t,
            'last_modified_t' => $this->last_modified_t,
            'product_name' => $this->product_name,
            'quantity' => $this->quantity,
            'brands' => $this->brands,
            'categories' => $this->categories,
            'labels' => $this->labels,
            'cities' => $this->cities,
            'purchase_places' => $this->purchase_places,
            'stores' => $this->stores,
            'ingredients_text' => $this->ingredients_text,
            'traces' => $this->traces,
            'serving_size' => $this->serving_size,
            'serving_quantity' => $this->serving_quantity,
            'nutriscore_score' => $this->nutriscore_score,
            'nutriscore_grade' => $this->nutriscore_grade,
            'main_category' => $this->main_category,
            'image_url' => $this->image_url,
        ];
    }
}