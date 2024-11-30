<?php

namespace Domain\Auth\Contracts;

use Domain\Auth\DTOs\NewUserDTO;
use Domain\Auth\Models\User;

interface RegisterUserContract
{
    public function __invoke(NewUserDTO $DTO): User;
}
