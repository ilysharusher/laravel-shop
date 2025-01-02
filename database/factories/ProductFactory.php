<?php

namespace Database\Factories;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
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
