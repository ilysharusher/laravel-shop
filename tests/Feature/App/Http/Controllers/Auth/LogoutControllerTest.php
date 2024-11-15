<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LogoutController;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Event::fake();

        $this->withExceptionHandling();
    }

    public function test_logout_success(): void
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);

        $this->delete(action([LogoutController::class, 'handle']))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
