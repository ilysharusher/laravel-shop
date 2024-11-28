<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ResetPasswordController;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    private string $token;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->token = Password::createToken($this->user);
    }

    public function test_page_success(): void
    {
        $this->get(action([ResetPasswordController::class, 'page'], ['token' => $this->token]))
            ->assertOk()
            ->assertViewIs('auth.reset-password');
    }

    public function test_handle_success(): void
    {
        $password = 'password';
        $password_confirmation = 'password';

        Password::shouldReceive('reset')
            ->once()
            ->withSomeofArgs([
                'email' => $this->user->email,
                'password' => $password,
                'password_confirmation' => $password_confirmation,
                'token' => $this->token
            ])
            ->andReturn(Password::PASSWORD_RESET);

        $response = $this->post(action([ResetPasswordController::class, 'handle']), [
            'email' => $this->user->email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
            'token' => $this->token
        ]);

        $response->assertRedirect(route('login'));
    }
}
