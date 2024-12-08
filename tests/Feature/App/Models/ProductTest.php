<?php

namespace Tests\Feature\App\Models;

use App\Models\Product;
use Support\ValueObjects\Price;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_price_casting(): void
    {
        $product = Product::factory()->create([
            'price' => 10000,
        ]);

        $this->assertInstanceOf(Price::class, $product->price);
        $this->assertEquals(10000, $product->price->rawAmount());
        $this->assertEquals(100.00, $product->price->amount());
    }

    public function test_price_setting(): void
    {
        $product = new Product();
        $product->price = new Price(15000);
        $product->fill(['title' => 'Test Product']);

        $product->save();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 15000,
        ]);
    }
}
