<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginFormRequest;
use App\Http\Requests\Auth\RegisterFormRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function login_store(LoginFormRequest $request): RedirectResponse
    {
        if (!auth()->attempt($request->validated())) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function register()
    {
        return view('auth.register');
    }

    public function register_store(RegisterFormRequest $request): RedirectResponse
    {
        $user = User::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        event(new Registered($user));
        auth()->login($user);

        return redirect()->intended(route('home'));
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
