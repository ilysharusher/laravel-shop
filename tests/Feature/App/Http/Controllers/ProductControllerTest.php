<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\ProductController;
use Database\Factories\ProductFactory;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function test_success_response(): void
    {
        $this->get(
            action(
                ProductController::class,
                ProductFactory::new()->createOne()
            )
        )->assertViewIs('product.show')->assertOk();
    }
}
