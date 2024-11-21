<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
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
