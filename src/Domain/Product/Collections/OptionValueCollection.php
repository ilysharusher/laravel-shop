<?php

namespace Domain\Product\Collections;

use Illuminate\Database\Eloquent\Collection;

class OptionValueCollection extends Collection
{
    public function keyValues()
    {
        return $this->mapToGroups(fn ($optionValue) => [
            $optionValue->option->title => $optionValue,
        ]);
    }
}
