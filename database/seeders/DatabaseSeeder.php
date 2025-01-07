<?php

namespace Database\Seeders;

use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\OptionFactory;
use Database\Factories\OptionValueFactory;
use Database\Factories\ProductFactory;
use Database\Factories\PropertyFactory;
use Database\Factories\UserFactory;
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
        UserFactory::new()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        BrandFactory::new()->count(20)->create();

        OptionFactory::new()->count(3)->create();

        $optionValues = OptionValueFactory::new()->count(10)->create();

        $properties = PropertyFactory::new()->count(5)->create();

        CategoryFactory::new()->count(30)->has(
            ProductFactory::new()->count(5)
                ->hasAttached($optionValues)
                ->hasAttached(
                    $properties,
                    fn () => ['value' => ucfirst(fake()->word())]
                )
        )->create();
    }
}
