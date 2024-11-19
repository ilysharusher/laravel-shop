<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    private function forgot_password(): User
    {
        $user = UserFactory::new()->create();

        $data = [
            'email' => $user->email,
        ];

        $this->post(
            action([ForgotPasswordController::class, 'handle']),
            $data
        )->assertRedirect(route('login'));

        return $user;
    }

    public function test_reset_password_page_success(): void
    {
        $user = $this->forgot_password();

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $this->get(
                action([ResetPasswordController::class, 'page'], $notification->token)
            )
                ->assertOk()
                ->assertViewIs('auth.reset-password');

            return true;
        });
    }

    public function test_reset_password_token_invalid(): void
    {
        $user = $this->forgot_password();

        $data = [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => 'invalid-token',
        ];

        $this->post(
            action([ResetPasswordController::class, 'handle']),
            $data
        )->assertSessionHasErrors('email');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_reset_password_store_success(): void
    {
        $user = $this->forgot_password();

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $data = [
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
                'token' => $notification->token,
            ];

            $this->post(
                action([ResetPasswordController::class, 'handle']),
                $data
            )->assertRedirect(route('login'));

            Event::assertDispatched(PasswordReset::class);

            $this->assertDatabaseMissing('password_reset_tokens', [
                'email' => $user->email,
            ]);

            return true;
        });
    }

    public function test_reset_password_password_confirmation_mismatch(): void
    {
        $user = $this->forgot_password();

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $data = [
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'different-password',
                'token' => $notification->token,
            ];

            $this->post(
                action([ResetPasswordController::class, 'handle']),
                $data
            )->assertSessionHasErrors('password');

            $this->assertDatabaseHas('password_reset_tokens', [
                'email' => $user->email,
            ]);

            return true;
        });
    }
}
