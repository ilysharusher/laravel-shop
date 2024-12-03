<?php

namespace Database\Factories;

use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->company(),
            'thumbnail' => fake()->randomThumbnail(
                'images/brands',
                'images/brands'
            ),
            'on_home_page' => fake()->boolean(),
            'sorting' => fake()->numberBetween(1, 1000),
        ];
    }
}
