<?php

namespace Domain\Catalog\Sorters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Stringable;

class Sorter
{
    public const KEY = 'sort';

    public function __construct(
        protected array $columns = []
    ) {
    }

    public function apply(Builder $query): Builder
    {
        $sortData = $this->sortData();

        return $query->when($sortData->contains($this->columns()), function (Builder $query) use ($sortData) {
            $query->orderBy(
                $sortData->remove('-')->toString(),
                $sortData->startsWith('-') ? 'desc' : 'asc'
            );
        });
    }

    public function isActive(string $column, string $direction = 'asc'): bool
    {
        $column = trim($column, '-');

        if (strtolower($direction) === 'desc') {
            $column = "-{$column}";
        }

        return request($this->key()) === $column;
    }

    public function sortData(): Stringable
    {
        return request()->str($this->key());
    }

    public function columns(): array
    {
        return $this->columns;
    }

    public function key(): string
    {
        return self::KEY;
    }
}
