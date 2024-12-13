<?php

namespace App\Menu;

use Countable;
use Illuminate\Support\Collection;
use IteratorAggregate;
use Support\Traits\Makeable;
use Traversable;

class Menu implements IteratorAggregate, Countable
{
    use Makeable;

    protected array $items = [];

    public function __construct(MenuItem ...$items)
    {
        $this->items = $items;
    }

    public function getIterator(): Traversable
    {
        return $this->all();
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function all(): Collection
    {
        return Collection::make($this->items);
    }

    public function add(MenuItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function remove(MenuItem $item): self
    {
        $this->items = array_filter($this->items, static fn ($i) => $i !== $item);

        return $this;
    }

    public function removeByUrl(string $url): self
    {
        $this->items = array_filter($this->items, static fn (MenuItem $i) => $i->getUrl() !== $url);

        return $this;
    }
}
