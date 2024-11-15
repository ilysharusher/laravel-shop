@extends('layouts.auth')

@section('title', 'Sign up')

@section('content')
    <x-forms.auth-forms title="Sign up to your account" action="{{ route('register.store') }}" method="POST">
        @csrf

        <x-inputs.text-input name="name" :error="$errors->has('name')" type="text" placeholder="Your name"
                             value="{{ old('name') }}"
                             required="true"/>
        @error('name')
        <x-inputs.error>{{ $message }}</x-inputs.error>
        @enderror

        <x-inputs.text-input name="email" :error="$errors->has('email')" type="email" placeholder="E-mail"
                             value="{{ old('email') }}"
                             required="true"/>
        @error('email')
        <x-inputs.error>{{ $message }}</x-inputs.error>
        @enderror

        <x-inputs.text-input name="password" :error="$errors->has('password')" type="password" placeholder="Password"
                             required="true"/>
        @error('password')
        <x-inputs.error>{{ $message }}</x-inputs.error>
        @enderror

        <x-inputs.text-input name="password_confirmation" :error="$errors->has('password_confirmation')" type="password"
                             placeholder="Confirm your password"
                             required="true"/>
        @error('password_confirmation')
        <x-inputs.error>{{ $message }}</x-inputs.error>
        @enderror

        <x-buttons.primary-button>Sign up</x-buttons.primary-button>

        <x-slot:social>
            <x-buttons.github-button href="{{ route('socialite.redirect', 'github') }}">GitHub</x-buttons.github-button>
        </x-slot:social>

        <x-slot:additionalButtons>
            <div class="text-xxs md:text-xs">
                <a href="{{ route('login') }}" class="text-white hover:text-white/70 font-bold">Login</a>
            </div>
        </x-slot:additionalButtons>
    </x-forms.auth-forms>
@endsection
