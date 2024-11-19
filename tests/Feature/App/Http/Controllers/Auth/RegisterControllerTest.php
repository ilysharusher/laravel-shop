<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    public function test_register_page_success(): void
    {
        $this->get(action([RegisterController::class, 'page']))
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
            action([RegisterController::class, 'handle']),
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

    public function test_register_store_invalid_email(): void
    {
        $data = [
            'name' => fake()->name,
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
        ]);

        $response = $this->post(
            action([RegisterController::class, 'handle']),
            $data
        );

        $response->assertInvalid(['email']);

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
        ]);

        Event::assertNotDispatched(Registered::class);
        Notification::assertNothingSent();
    }

    public function test_register_store_invalid_password(): void
    {
        $data = [
            'name' => fake()->name,
            'email' => 'test@gmail.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
        ]);

        $response = $this->post(
            action([RegisterController::class, 'handle']),
            $data
        );

        $response->assertInvalid(['password']);

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
        ]);

        Event::assertNotDispatched(Registered::class);
        Notification::assertNothingSent();
    }

    public function test_register_store_invalid_password_confirmation(): void
    {
        $data = [
            'name' => fake()->name,
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'different_password',
        ];

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
        ]);

        $response = $this->post(
            action([RegisterController::class, 'handle']),
            $data
        );

        $response->assertInvalid(['password']);

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
        ]);

        Event::assertNotDispatched(Registered::class);
        Notification::assertNothingSent();
    }

    public function test_register_store_user_already_exists(): void
    {
        $email = 'test@gmail.com';

        UserFactory::new()->create([
            'email' => $email,
        ]);

        $data = [
            'name' => fake()->name,
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);

        $response = $this->post(
            action([RegisterController::class, 'handle']),
            $data
        );

        $response->assertInvalid(['email']);

        $this->assertDatabaseCount('users', 1);

        Event::assertNotDispatched(Registered::class);
        Notification::assertNothingSent();
    }
}
