<?php

namespace Domain\Auth\Contracts;

interface SocialAuthContract
{
    public function __invoke(string $driver): void;
}
