<?php

namespace Tests\Feature\Domain\Auth\Actions;

use Domain\Auth\Contracts\RegisterUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Tests\TestCase;

class RegisterUserActionTest extends TestCase
{
    public function test_register_user_success(): void
    {
        $email = 'test@gmail.com';

        $this->assertDatabaseMissing('users', [
            'email' => $email
        ]);

        $action = app(RegisterUserContract::class);

        $action(NewUserDTO::make([
            'John Doe',
            $email,
            'password',
        ]));

        $this->assertDatabaseHas('users', [
            'email' => $email
        ]);
    }
}
