@extends('layouts.auth')

@section('title', 'Sign in')

@section('content')
    <x-forms.auth-forms title="Sign in to your account" action="{{ route('login.store') }}" method="POST">
        @csrf

        <x-inputs.text-input name="email" :error="$errors->has('email')" type="email" placeholder="E-mail"
                             value="{{ old('email') }}"
                             required="true"/>
        @error('email')
        <x-inputs.error>{{ $message }}</x-inputs.error>
        @enderror

        <x-inputs.text-input name="password" :error="$errors->has('email')" type="password" placeholder="Password"
                             required="true"/>

        <x-buttons.primary-button>Sign in</x-buttons.primary-button>

        <x-slot:social>
            <x-buttons.github-button href="{{ route('socialite.redirect', 'github') }}">GitHub</x-buttons.github-button>
        </x-slot:social>

        <x-slot:additionalButtons>
            <div class="text-xxs md:text-xs">
                <a href="{{ route('password.request') }}" class="text-white hover:text-white/70 font-bold">Forgot password?</a>
            </div>
            <div class="text-xxs md:text-xs">
                <a href="{{ route('register') }}" class="text-white hover:text-white/70 font-bold">Registration</a>
            </div>
        </x-slot:additionalButtons>
    </x-forms.auth-forms>
@endsection
