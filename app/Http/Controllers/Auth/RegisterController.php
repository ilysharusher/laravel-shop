<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterFormRequest;
use Domain\Auth\Contracts\RegisterUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function page()
    {
        return view('auth.register');
    }

    public function handle(RegisterFormRequest $request, RegisterUserContract $contract): RedirectResponse
    {
        $user = $contract(NewUserDTO::fromRequest($request));

        auth()->login($user);

        return redirect()->intended(route('home'));
    }
}
