<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function borrow(Request $request)
    {
        try {
            $user = auth()->user();

            // Find the book
            $book = Book::findOrFail($request['book_id']);

            // Check if the user already borrowed the book
            if ($user->books()->where('book_id', $book->id)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have already borrowed this book.'
                ], 400);
            }

            // Borrow the book
            $user->books()->attach($book, [
                'borrowed_at' => now(),
            ]);

            // Update the book quantities
            $book->update([
                'is_active' => $book->available_quantity == 1 ? false : true ,
                'available_quantity' => $book->available_quantity - 1,
                'borrowed_quantity' => $book->borrowed_quantity + 1,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Book borrowed successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function backup(Request $request)
    {
        try {
            $user = auth()->user();

            // Find the book
            $book = Book::findOrFail($request['book_id']);

            // Check if the user actually borrowed the book
            $borrowedBook = $user->books()->where('book_id', $book->id)->first();
            if (!$borrowedBook) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You cannot return a book you have not borrowed.'
                ], 400);
            }

            // Return the book
            $user->books()->detach($book);

            // Update the book quantities
            $book->update([
                'is_active' => true,
                'available_quantity' => $book->available_quantity + 1,
                'borrowed_quantity' => $book->borrowed_quantity - 1,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Book returned successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function borrowedBooks()
    {
        try {
            $user = auth()->user();

            // Get all books borrowed by the user
            $books = $user->books()->get();

            return response()->json([
                'status' => 'success',
                'data' => $books
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function analyticsBooksBorrowed()
    {
        try {
            // Count total books borrowed
            $totalBooksBorrowed = DB::table('book_user')->count();

            $borrowedBooks = DB::table('book_user')
                ->join('users', 'book_user.user_id', '=', 'users.id')
                ->join('books', 'book_user.book_id', '=', 'books.id')
                ->select(
                    'users.name as user_name',
                    'users.email as user_email',
                    'books.title as book_title',
                    'books.isbn as book_isbn'
                )
                ->get();

            return response()->json([
                'status' => 'success',
                'total_borrowed_books' => $totalBooksBorrowed,
                'borrowed_books' => $borrowedBooks,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
