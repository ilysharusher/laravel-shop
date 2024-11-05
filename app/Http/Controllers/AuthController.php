<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordFormRequest;
use App\Http\Requests\Auth\LoginFormRequest;
use App\Http\Requests\Auth\RegisterFormRequest;
use App\Http\Requests\Auth\ResetPasswordFormRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;

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

    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::query()->updateOrCreate([
            'github_id' => $githubUser->id,
        ], [
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'password' => bcrypt(str()->random(60)),
        ]);

        auth()->login($user);

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

    public function forgot_password()
    {
        return view('auth.forgot-password');
    }

    public function forgot_password_store(ForgotPasswordFormRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            flash()->info(__($status));

            return redirect()->route('login');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function reset_password(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset_password_store(ResetPasswordFormRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            static function (User $user, string $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(str()->random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            flash()->info(__($status));

            return redirect()->route('login');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
