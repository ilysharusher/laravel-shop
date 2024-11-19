<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController;
use Database\Factories\UserFactory;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
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

    public function test_login_store_wrong_password(): void
    {
        $password = 'wrong_password';

        $user = UserFactory::new()->create([
            'email' => 'test@gmail.com',
            'password' => bcrypt('correct_password'),
        ]);

        $data = [
            'email' => $user->email,
            'password' => $password,
        ];

        $this->assertGuest();

        $this->post(
            action([LoginController::class, 'handle']),
            $data
        )
            ->assertInvalid()
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_store_user_not_found(): void
    {
        $data = [
            'email' => 'nonexistent@gmail.com',
            'password' => 'password',
        ];

        $this->assertGuest();

        $this->post(
            action([LoginController::class, 'handle']),
            $data
        )
            ->assertInvalid()
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
