<?php

namespace Domain\Auth\Contracts;

use Domain\Auth\Models\User;

interface SocialAuthContract
{
    public function __invoke(string $driver): User;
}
