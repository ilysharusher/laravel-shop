<?php

namespace Tests\Unit\Support\Casts;

use Illuminate\Database\Eloquent\Model;
use Support\Casts\PriceCast;
use Support\ValueObjects\Price;
use Tests\TestCase;

class PriceCastTest extends TestCase
{
    public function test_get_casts_raw_value_to_price_object(): void
    {
        $cast = new PriceCast();

        $model = $this->mock(Model::class);
        $price = $cast->get($model, 'price', 10000, []);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals(10000, $price->rawAmount());
        $this->assertEquals(100.00, $price->amount());
    }

    public function test_set_casts_price_object_to_raw_value(): void
    {
        $cast = new PriceCast();

        $model = $this->mock(Model::class);
        $price = new Price(10000);
        $rawValue = $cast->set($model, 'price', $price, []);

        $this->assertEquals(10000, $rawValue);
    }

    public function test_set_casts_non_price_value_to_raw_value(): void
    {
        $cast = new PriceCast();

        $model = $this->mock(Model::class);
        $rawValue = $cast->set($model, 'price', 10000, []);

        $this->assertEquals(10000, $rawValue);
    }
}
