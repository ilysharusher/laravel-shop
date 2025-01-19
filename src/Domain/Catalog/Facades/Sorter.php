<?php

namespace Domain\Catalog\Facades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;
use Domain\Catalog\Sorters\Sorter as SorterClass;

/**
 * @see SorterClass
 * @method static Builder apply(Builder $query)
 */
class Sorter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SorterClass::class;
    }
}
