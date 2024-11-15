<?php

namespace Database\Seeders;

use App\Models\Brand;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // TODO Class "Database\Factories\Domain\Auth\Models\UserFactory" not found
        Brand::factory(20)->create();

        Category::factory(3)->has(
            Product::factory(3)
        )->create();
    }
}
