<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LogoutController;
use Database\Factories\UserFactory;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    public function test_logout_success(): void
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);

        $this->delete(action([LogoutController::class, 'handle']))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_logout_fail(): void
    {
        $this->assertGuest();

        $this->delete(action([LogoutController::class, 'handle']))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
