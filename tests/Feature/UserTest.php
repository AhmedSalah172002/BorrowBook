<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_user_can_register(): void
    {
        $userData = [
            "name" => "bako1de6308",
            "email" => "bakode16308@xcmexico.com",
            "password" => "123456789",
            "password_confirmation" => "123456789",
            "phone_number" => "12345678901",
            "address" => "1234 Elm Street, Springfield, IL, 62704",
            "role" => "user"
        ];

        $response = $this->post(route('api.register'), $userData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }

    public function test_register_without_field(): void
    {
        $userData = [
            "email" => "bakod12e16308@xcmexico.com",
            "password" => "123456789",
            "password_confirmation" => "123456789",
            "phone_number" => "12345678901",
            "address" => "1234 Elm Street, Springfield, IL, 62704",
            "role" => "user"
        ];
        $response = $this->post(route('api.register'), $userData);
        $response->assertStatus(422);
    }
}
