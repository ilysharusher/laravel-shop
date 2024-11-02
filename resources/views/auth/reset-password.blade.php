@extends('layouts.auth')

@section('title', 'Password recovery')

@section('content')
    <x-forms.auth-forms title="Password recovery" action="{{ route('password.update') }}" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <x-inputs.text-input name="email" :error="$errors->has('email')" type="email" placeholder="E-mail"
                             value="{{ request()->get('email') }}"
                             required="true"/>
        @error('email')
        <x-inputs.error>{{ $message }}</x-inputs.error>
        @enderror

        <x-inputs.text-input name="password" :error="$errors->has('password')" type="password" placeholder="Type your new password"
                             required="true"/>
        @error('password')
        <x-inputs.error>{{ $message }}</x-inputs.error>
        @enderror

        <x-inputs.text-input name="password_confirmation" :error="$errors->has('password_confirmation')" type="password"
                             placeholder="Confirm your new password"
                             required="true"/>
        @error('password_confirmation')
        <x-inputs.error>{{ $message }}</x-inputs.error>
        @enderror

        <x-buttons.primary-button>Reset password</x-buttons.primary-button>
    </x-forms.auth-forms>
@endsection
