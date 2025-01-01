<?php

namespace Domain\Catalog\Sorters;

use Stringable;

class Sorter implements Stringable
{
    protected array $columns = [
        'default' => 'Default',
        'title' => 'By name',
        'price' => 'By price (low to high)',
        '-price' => 'By price (high to low)',
    ];

    public function __toString(): string
    {
        return view('catalog.sorter.sort', [
            'sorter' => $this,
        ])->render();
    }

    public function columns(): array
    {
        return $this->columns;
    }

    public function requestValue(): string
    {
        return request()->input('sort', 'default');
    }

    public function apply($query): void
    {
        $column = str($this->requestValue());

        $query->when($column->value(), function ($query) use ($column) {
            if ($column->value() === 'default') {
                return;
            }

            $query->orderBy(
                $column->remove('-'),
                $column->contains('-') ? 'desc' : 'asc'
            );
        });
    }
}
