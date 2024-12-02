<?php

namespace Tests\Feature\Domain\Auth\DTOs;

use App\Http\Requests\Auth\RegisterFormRequest;
use Domain\Auth\DTOs\NewUserDTO;
use Tests\TestCase;

class NewUserDTOTest extends TestCase
{
    public function test_instance_created_from_request(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'test@gmail.com',
            'password' => 'password',
        ];

        $DTO = NewUserDTO::fromRequest(new RegisterFormRequest($data));

        $this->assertInstanceOf(NewUserDTO::class, $DTO);
    }
}
