<?php

namespace Support\Traits;

trait Makeable
{
    public static function make(array $arguments): static
    {
        return new static(...$arguments);
    }
}
