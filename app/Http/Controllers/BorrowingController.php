<?php

namespace App\Http\Controllers;

use App\Jobs\BorrowBook;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function borrow(Request $request, BookService $bookService)
    {
        DB::beginTransaction();

        return $this->handleRequest(function () use ($request, $bookService) {
            $user = auth()->user();
            $book = Book::findOrFail($request['book_id']);
            $bookService->borrow($book, $user);
            BorrowBook::dispatch($user, $book);
            DB::commit();
            return $this->successResponse(['message' => 'Book borrowed successfully'], 201);
        });
    }

    public function backup(Request $request, BookService $bookService)
    {
        DB::beginTransaction();

        return $this->handleRequest(function () use ($request, $bookService) {
            $user = auth()->user();
            $book = Book::findOrFail($request['book_id']);
            $bookService->returnBook($book, $user);
            DB::commit();
            return $this->successResponse(['message' => 'Book returned successfully'], 201);
        });
    }

    public function analyticsBooksBorrowed(BookService $bookService)
    {
        return $this->handleRequest(function () use ($bookService) {
            return $bookService->BorrowedBook();
        });
    }

    public function borrowedBooks()
    {
        $user = auth()->user();
        return $this->successResponse($user->books()->get());
    }

}
