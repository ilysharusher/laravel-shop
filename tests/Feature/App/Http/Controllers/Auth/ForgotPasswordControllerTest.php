<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\PasswordResetLinkSent;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
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

    public function test_forgot_password_page_success(): void
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
            ->assertOk()
            ->assertViewIs('auth.forgot-password');
    }

    public function test_forgot_password_store(): void
    {
        $user = $this->forgot_password();

        Notification::assertSentTo($user, ResetPassword::class);
        Event::assertDispatched(PasswordResetLinkSent::class);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_forgot_password_email_not_found(): void
    {
        $data = [
            'email' => 'nonexistent@example.com',
        ];

        $this->post(
            action([ForgotPasswordController::class, 'handle']),
            $data
        )->assertSessionHasErrors('email');
    }
}
