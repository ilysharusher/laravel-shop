<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\HomeController;
use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test_success_response(): void
    {
        $this->actingAs(UserFactory::new()->createOne());

        CategoryFactory::new()->count(3)->create([
            'on_home_page' => true,
            'sorting' => 2,
        ]);

        $firstCategory = CategoryFactory::new()->createOne([
            'on_home_page' => true,
            'sorting' => 1,
        ]);

        BrandFactory::new()->count(3)->create([
            'on_home_page' => true,
            'sorting' => 2,
        ]);

        $firstBrand = BrandFactory::new()->createOne([
            'on_home_page' => true,
            'sorting' => 1,
        ]);

        ProductFactory::new()->count(3)->create([
            'on_home_page' => true,
            'sorting' => 2,
        ]);

        $firstProduct = ProductFactory::new()->createOne([
            'on_home_page' => true,
            'sorting' => 1,
        ]);

        $this->get(action(HomeController::class))
            ->assertOk()
            ->assertViewIs('index')
            ->assertViewHas('categories.0', $firstCategory)
            ->assertViewHas('brands.0', $firstBrand)
            ->assertViewHas('products.0', $firstProduct);
    }
}
