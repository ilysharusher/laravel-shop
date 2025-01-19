<?php

namespace Database\Factories;

use Domain\Catalog\Models\Brand;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'thumbnail' => fake()->randomThumbnail(
                'images/products',
                'images/products'
            ),
            'description' => fake()->realText(),
            'price' => fake()->numberBetween(1000, 1000000),
            'on_home_page' => fake()->boolean(),
            'sorting' => fake()->numberBetween(1, 1000),
            'brand_id' => Brand::query()->inRandomOrder()->first()
        ];
    }
}
