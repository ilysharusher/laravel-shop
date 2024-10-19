<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'thumbnail' => fake()->file(
                base_path('tests/Fixtures/images/products'),
                storage_path('app/public/images/products'),
                false
            ), // TODO: Move out this logic to a separate class with creating directories
            'price' => fake()->numberBetween(100, 1000),
            'brand_id' => Brand::query()->inRandomOrder()->first()
        ];
    }
}
