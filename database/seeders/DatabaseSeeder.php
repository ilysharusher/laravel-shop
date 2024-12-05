<?php

namespace Database\Seeders;

use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Illuminate\Database\Seeder;
use Random\RandomException;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws RandomException
     */
    public function run(): void
    {
        BrandFactory::new()->count(3)->create();

        CategoryFactory::new()->count(10)->has(
            ProductFactory::new()->count(random_int(5, 10))
        )->create();
    }
}
