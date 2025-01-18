<?php

namespace Tests\Feature\App\Jobs;

use App\Jobs\ProductJsonProperties;
use Database\Factories\ProductFactory;
use Database\Factories\PropertyFactory;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProductJsonPropertiesTest extends TestCase
{
    public function test_json_properties_created_successfully(): void
    {
        $queue = Queue::getFacadeRoot();

        Queue::fake(ProductJsonProperties::class);

        $properties = PropertyFactory::new()->count(3)->create();

        $product = ProductFactory::new()
            ->hasAttached(
                $properties,
                fn () => ['value' => ucfirst(fake()->word())]
            )->create();

        $this->assertEmpty($product->json_properties);

        Queue::swap($queue);

        ProductJsonProperties::dispatchSync($product);

        $product->refresh();

        $this->assertNotEmpty($product->json_properties);
    }
}
