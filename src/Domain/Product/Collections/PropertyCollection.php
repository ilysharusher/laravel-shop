<?php

namespace Domain\Product\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class PropertyCollection extends Collection
{
    public function keyValues(): SupportCollection
    {
        return $this->mapWithKeys(fn ($property) => [
            $property->title => $property->pivot->value,
        ]);
    }
}
