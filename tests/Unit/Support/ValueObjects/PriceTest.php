<?php

namespace Tests\Unit\Support\ValueObjects;

use InvalidArgumentException;
use Support\ValueObjects\Price;
use Tests\TestCase;

class PriceTest extends TestCase
{
    public function test_price_creation_success(): void
    {
        $price = Price::make(1000);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals('10,00 $', (string)$price);
        $this->assertEquals(10, $price->amount());
        $this->assertEquals(1000, $price->rawAmount());
        $this->assertEquals('USD', $price->currency());
        $this->assertEquals('$', $price->currencySymbol());
    }

    public function test_price_creation_with_negative_amount_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Price::make(-1000);
    }

    public function test_price_creation_with_invalid_currency_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Price::make(1000, 'RUB');
    }
}
