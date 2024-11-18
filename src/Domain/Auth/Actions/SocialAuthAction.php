<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\SocialAuthContract;
use Domain\Auth\Models\User;
use DomainException;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthAction implements SocialAuthContract
{
    public function __invoke(string $driver): void
    {
        try {
            $socialUser = Socialite::driver($driver)->user();

            $user = User::query()->updateOrCreate([
                $driver . '_id' => $socialUser->id,
            ], [
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'password' => bcrypt(str()->random(60)),
            ]);

            auth()->login($user);
        } catch (Throwable $e) {
            throw new DomainException('Invalid driver or user not found');
        }
    }
}
