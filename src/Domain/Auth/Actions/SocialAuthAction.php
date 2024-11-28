<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\SocialAuthContract;
use Domain\Auth\Models\User;
use DomainException;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthAction implements SocialAuthContract
{
    public function __invoke(string $driver): User
    {
        try {
            $socialUser = Socialite::driver($driver)->user();

            return User::query()->updateOrCreate([
                $driver . '_id' => $socialUser->getId(),
            ], [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(str()->random(60)),
            ]);
        } catch (Throwable $e) {
            throw new DomainException('Invalid driver or user not found');
        }
    }
}
