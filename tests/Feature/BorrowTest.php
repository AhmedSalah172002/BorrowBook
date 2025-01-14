<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BorrowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_borrow_and_return_book(): void
    {
        // factory to create user and factory
        $user = User::factory()->create();
        $book = Book::factory()->create();

        // login with new user
        $response = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => '123456789',
        ]);

        // correct borrow
        $response = $this->post(route('api.borrow'), ['book_id' => $book->id]);
        $response->assertStatus(201);

        // book already borrowed
        $response = $this->post(route('api.borrow'), ['book_id' => $book->id]);
        $response->assertStatus(400);

        //book not available
        $newBook = Book::factory()->create([
            'available_quantity' => 0,
        ]);
        $response = $this->post(route('api.borrow'), ['book_id' => $newBook->id]);
        $response->assertStatus(400);

        //correct return
        $response = $this->post(route('api.return'), ['book_id' => $book->id]);
        $response->assertStatus(201);

        // book already return
        $response = $this->post(route('api.return'), ['book_id' => $book->id]);
        $response->assertStatus(400);
    }
}
