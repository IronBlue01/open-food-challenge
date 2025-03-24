<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status'            => 'required|string|in:published,draft,trash',
            'product_name'      => 'nullable|string',
            'quantity'          => 'nullable|string',
            'brands'            => 'nullable|string',
            'categories'        => 'nullable|string',
            'labels'            => 'nullable|string',
            'cities'            => 'nullable|string',
            'purchase_places'   => 'nullable|string',
            'stores'            => 'nullable|string',
            'ingredients_text'  => 'nullable|string',
            'traces'            => 'nullable|string',
            'serving_size'      => 'nullable|string',
            'serving_quantity'  => 'nullable|numeric',
            'nutriscore_score'  => 'nullable|integer',
            'nutriscore_grade'  => 'nullable|string|in:a,b,c,d,e',
            'main_category'     => 'nullable|string',
            'image_url'         => 'nullable|url',
        ];
    }
}
