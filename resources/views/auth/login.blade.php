@extends('layouts.auth')

@section('title', 'Sign in')

@section('content')
    <div class="max-w-[640px] mt-12 mx-auto p-6 xs:p-8 md:p-12 2xl:p-16 rounded-[20px] bg-purple">
        <h1 class="mb-5 text-lg font-semibold">Sign in to your account</h1>
        <form class="space-y-3">

            <x-inputs.text-input name="email" :error="$errors->has('email')" type="email" placeholder="E-mail"
                                required="true"/>
            @error('email')
            <x-inputs.error>{{ $message }}</x-inputs.error>
            @enderror

            <x-inputs.text-input name="password" :error="$errors->has('password')" type="password" placeholder="Password"
                                required="true"/>

            <x-buttons.primary-button>Sign in</x-buttons.primary-button>
        </form>
        <ul class="space-y-3 mt-3">
            <li>
                <x-buttons.github-button href="#">GitHub</x-buttons.github-button>
            </li>
        </ul>
        <div class="space-y-3 mt-5">
            <div class="text-xxs md:text-xs"><a href="#"
                                                class="text-white hover:text-white/70 font-bold">Forgot password?</a>
            </div>
            <div class="text-xxs md:text-xs"><a href="#"
                                                class="text-white hover:text-white/70 font-bold">Registration</a>
            </div>
        </div>
        <ul class="flex flex-col md:flex-row justify-between gap-3 md:gap-4 mt-14 md:mt-20">
            <li>
                <a href="#" class="inline-block text-white hover:text-white/70 text-xxs md:text-xs font-medium"
                   target="_blank" rel="noopener">Terms of service</a>
            </li>
            <li class="hidden md:block">
                <div class="h-full w-[2px] bg-white/20"></div>
            </li>
            <li>
                <a href="#" class="inline-block text-white hover:text-white/70 text-xxs md:text-xs font-medium"
                   target="_blank" rel="noopener">Privacy policy</a>
            </li>
        </ul>
    </div>
@endsection
