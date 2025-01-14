<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_not_admin_cannot_create_book(): void
    {
        $user = User::factory()->create();

        // login with new user
        $response = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => '123456789',
        ]);

        // not allow to any one except admin to create book
        $response = $this->post(route('api.books.store'), [
            'title' => 'test',
            'author' => 'test',
            'description' => 'test',
            'available_quantity' => 10,
            'genre_id' => 1,
            'pages' => 100,
            'publication_year' => 2021,
            'cover_image' => 'test',
            'is_active' => true,
            'borrowed_quantity' => 0,
            'isbn' => '123456789',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_book(): void
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        // login with new user
        $response = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => '123456789',
        ]);

        // not allow to any one except admin to create book
        $response = $this->post(route('api.books.store'), [
            'title' => 'test',
            'author' => 'test',
            'description' => 'test',
            'available_quantity' => 10,
            'genre_id' => 1,
            'pages' => 100,
            'publication_year' => 2021,
            'cover_image' => 'test',
            'is_active' => true,
            'borrowed_quantity' => 0,
            'isbn' => '123456789',
        ]);

        $response->assertStatus(201);
    }

}
