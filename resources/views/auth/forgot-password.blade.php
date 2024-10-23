@extends('layouts.auth')

@section('title', 'Forgot password')

@section('content')
    <x-forms.auth-forms title="Forgot password" action="">
        @csrf

        <x-inputs.text-input name="email" :error="$errors->has('email')" type="email" placeholder="E-mail for password reset"
                             required="true"/>
        @error('email')
        <x-inputs.error>{{ $message }}</x-inputs.error>
        @enderror

        <x-buttons.primary-button>Send password reset link</x-buttons.primary-button>

        <x-slot:additionalButtons>
            <div class="text-xxs md:text-xs">
                <a href="{{ route('login') }}" class="text-white hover:text-white/70 font-bold">I remember my password</a>
            </div>
        </x-slot:additionalButtons>
    </x-forms.auth-forms>
@endsection
