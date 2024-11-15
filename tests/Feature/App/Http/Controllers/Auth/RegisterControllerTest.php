<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Event::fake();

        $this->withExceptionHandling();
    }

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
}
