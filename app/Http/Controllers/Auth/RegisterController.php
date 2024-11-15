<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterFormRequest;
use Domain\Auth\Contracts\RegisterUserContract;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function page()
    {
        return view('auth.register');
    }

    public function handle(RegisterFormRequest $request, RegisterUserContract $contract): RedirectResponse
    {
        $contract($request->name, $request->email, $request->password);

        return redirect()->intended(route('home'));
    }
}
