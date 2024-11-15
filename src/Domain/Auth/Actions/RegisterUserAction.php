<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\RegisterUserContract;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;

class RegisterUserAction implements RegisterUserContract
{
    public function __invoke(string $name, string $email, string $password): void
    {
        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        event(new Registered($user));
        auth()->login($user);
    }
}
