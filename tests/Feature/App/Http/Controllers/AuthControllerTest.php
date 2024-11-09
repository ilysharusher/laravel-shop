<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Listeners\SendEmailNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\PasswordResetLinkSent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Event::fake();

        $this->withExceptionHandling();
    }

    public function test_login_page_success(): void
    {
        $this->get(action([AuthController::class, 'login']))
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    public function test_login_store_success(): void
    {
        $password = 'password';

        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => bcrypt($password),
        ]);

        $data = [
            'email' => $user->email,
            'password' => $password,
        ];

        $this->assertGuest();

        $this->post(
            action([AuthController::class, 'login_store']),
            $data
        )->assertValid()->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_register_page_success(): void
    {
        $this->get(action([AuthController::class, 'register']))
            ->assertOk()
            ->assertViewIs('auth.register');
    }

    public function test_register_store_success(): void
    {
        $data = [
            'name' => fake()->name,
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
        ]);

        $response = $this->post(
            action([AuthController::class, 'register_store']),
            $data
        )->assertValid();

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);

        $user = User::query()->where('email', $data['email'])->first();

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailNewUserListener::class);

        $listener = new SendEmailNewUserListener();
        $listener->handle(new Registered($user));

        Notification::assertSentTo($user, NewUserNotification::class);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('home'));
    }

    public function test_forgot_password_page_success(): void
    {
        $this->get(action([AuthController::class, 'forgot_password']))
            ->assertOk()
            ->assertViewIs('auth.forgot-password');
    }

    private function forgot_password(): User
    {
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
        ];

        $this->post(
            action([AuthController::class, 'forgot_password_store']),
            $data
        )->assertRedirect(route('login'));

        return $user;
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

    public function test_reset_password_page_success(): void
    {
        $user = $this->forgot_password();

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $this->get(
                action([AuthController::class, 'reset_password'], $notification->token)
            )
                ->assertOk()
                ->assertViewIs('auth.reset-password');

            return true;
        });
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
                action([AuthController::class, 'reset_password_store']),
                $data
            )->assertRedirect(route('login'));

            $this->assertDatabaseMissing('password_reset_tokens', [
                'email' => $user->email,
            ]);

            return true;
        });
    }

    public function test_logout_success(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);

        $this->delete(action([AuthController::class, 'logout']))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
