<?php

namespace Domain\Product\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class OptionValueCollection extends Collection
{
    public function keyValues(): OptionValueCollection|SupportCollection
    {
        return $this->mapToGroups(fn ($optionValue) => [
            $optionValue->option->title => $optionValue,
        ]);
    }
}
