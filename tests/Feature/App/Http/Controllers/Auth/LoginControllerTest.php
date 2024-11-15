<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LoginControllerTest extends TestCase
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
        $this->get(action([LoginController::class, 'page']))
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    public function test_login_store_success(): void
    {
        $password = 'password';

        $user = UserFactory::new()->create([
            'email' => 'test@gmail.com',
            'password' => bcrypt($password),
        ]);

        $data = [
            'email' => $user->email,
            'password' => $password,
        ];

        $this->assertGuest();

        $this->post(
            action([LoginController::class, 'handle']),
            $data
        )->assertValid()->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }
}
