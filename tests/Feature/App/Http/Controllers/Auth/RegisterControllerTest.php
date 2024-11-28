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
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    protected array $request = [
        'name' => 'Name',
        'email' => 'example@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    private function register(): TestResponse
    {
        return $this->post(
            action([RegisterController::class, 'handle']),
            $this->request
        );
    }

    private function currentUser(): User
    {
        return User::query()->where('email', $this->request['email'])->first();
    }

    public function test_page_success(): void
    {
        $this->get(action([RegisterController::class, 'page']))
            ->assertOk()
            ->assertViewIs('auth.register');
    }

    public function test_validation_success(): void
    {
        $this->register()
            ->assertValid();
    }

    public function test_bad_password_confirmation(): void
    {
        $this->request['password_confirmation'] = 'bad_password';

        $this->register()
            ->assertInvalid('password');
    }

    public function test_bad_name(): void
    {
        $this->request['name'] = '';

        $this->register()
            ->assertInvalid('name');
    }

    public function test_bad_email(): void
    {
        $this->request['email'] = 'bad_email';

        $this->register()
            ->assertInvalid('email');
    }

    public function test_email_already_exists(): void
    {
        UserFactory::new()->create([
            'email' => $this->request['email'],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $this->request['email'],
        ]);

        $this->register()
            ->assertInvalid('email');
    }

    public function test_user_created_success(): void
    {
        $this->assertDatabaseMissing('users', [
            'email' => $this->request['email'],
        ]);

        $this->register();

        $this->assertDatabaseHas('users', [
            'email' => $this->request['email'],
        ]);
    }

    public function test_event_and_listener_success(): void
    {
        Event::fake();

        $this->register();

        Event::assertDispatched(Registered::class);
        Event::assertListening(
            Registered::class,
            SendEmailNewUserListener::class
        );
    }

    public function test_notification_sent(): void
    {
        $this->register();

        Notification::assertSentTo(
            $this->currentUser(),
            NewUserNotification::class
        );
    }

    public function test_user_authenticated_after_register(): void
    {
        $this->register()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($this->currentUser());
    }
}
