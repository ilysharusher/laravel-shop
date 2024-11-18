<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Contracts\SocialAuthContract;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirect(string $driver): RedirectResponse
    {
        try {
            return Socialite::driver($driver)->redirect();
        } catch (Throwable $e) {
            throw new DomainException('Invalid driver');
        }
    }

    public function callback(string $driver, SocialAuthContract $contract): RedirectResponse
    {
        $contract($driver);

        return redirect()->intended(route('home'));
    }
}
