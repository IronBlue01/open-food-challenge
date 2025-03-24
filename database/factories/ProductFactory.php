<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code'             => $this->faker->unique()->numberBetween(10000000, 99999999),
            'status'           => $this->faker->randomElement(['published', 'draft', 'trash']),
            'imported_t'       => '2020-02-07T16:00:00Z',
            'url'              => fn () => 'https://world.openfoodfacts.org/product/' . $this->faker->unique()->numberBetween(10000000, 99999999),
            'creator'          => $this->faker->userName(),
            'created_t'        => $this->faker->unixTime(),
            'last_modified_t'  => $this->faker->unixTime(),
            'product_name'     => $this->faker->words(3, true),
            'quantity'         => $this->faker->randomElement(['380 g (6 x 2 u.)', '500 g', '1 kg']),
            'brands'           => $this->faker->company(),
            'categories'       => 'Lanches comida, Lanches doces, Biscoitos e Bolos, Bolos, Madalenas',
            'labels'           => 'Contem gluten, Contém derivados de ovos, Contém ovos',
            'cities'           => '',
            'purchase_places'  => $this->faker->city() . ',' . $this->faker->country(),
            'stores'           => $this->faker->company(),
            'ingredients_text' => 'farinha de trigo, açúcar, óleo vegetal de girassol, clara de ovo, ovo, humidificante (sorbitol), levedantes químicos (difosfato dissódico, hidrogenocarbonato de sódio), xarope de glucose-frutose, sal, aroma',
            'traces'           => 'Frutos de casca rija,Leite,Soja,Sementes de sésamo,Produtos à base de sementes de sésamo',
            'serving_size'     => 'madalena 31.7 g',
            'serving_quantity' => 31.7,
            'nutriscore_score' => $this->faker->numberBetween(-15, 40),
            'nutriscore_grade' => $this->faker->randomElement(['a', 'b', 'c', 'd', 'e']),
            'main_category'    => 'en:madeleines',
            'image_url'        => 'https://static.openfoodfacts.org/images/products/20221126/front_pt.5.400.jpg',
        ];
    }
}
