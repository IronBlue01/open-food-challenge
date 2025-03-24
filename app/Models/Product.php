<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Produto",
 *     required={"id", "name", "price"},
 * 
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Arroz Branco"),
 *     @OA\Property(property="price", type="number", format="float", example=19.90),
 *     @OA\Property(property="stock", type="integer", example=50),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-22T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-22T12:00:00Z")
 * )
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
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
    ];
}
