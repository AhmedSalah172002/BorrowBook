<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BookService extends Controller
{

    public function borrow($book, $user)
    {

        if ($book->available_quantity == 0) {
            throw new \Exception("This book is not available.");
        }
        if ($user->books()->where('book_id', $book->id)->exists()) {
            throw new \Exception("You have already borrowed this book.");
        }
        $user->books()->attach($book, ['borrowed_at' => now(),]);
        $book->update([
            'is_active' => $book->available_quantity == 1 ? false : true,
            'available_quantity' => $book->available_quantity - 1,
            'borrowed_quantity' => $book->borrowed_quantity + 1,
        ]);
    }

    public function returnBook($book, $user)
    {

        $borrowedBook = $user->books()->where('book_id', $book->id)->first();
        if (!$borrowedBook) {
            throw new \Exception("You cannot return a book you have not borrowed.");
        }
        $user->books()->detach($book);
        $book->update([
            'is_active' => true,
            'available_quantity' => $book->available_quantity + 1,
            'borrowed_quantity' => $book->borrowed_quantity - 1,
        ]);
    }

    public function BorrowedBook()
    {
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
        return $this->successResponse(['total_borrowed_books' => $totalBooksBorrowed, 'borrowed_books' => $borrowedBooks]);
    }

    public function BorrowedBookInLast7Months()
    {
        return $this->handleRequest(function () {
            $last7monthsTimestamp = collect(range(1, 7))->map(fn($i) => now('UTC')->subMonths($i)->format('Y-m'));
            $borrowedBooks = [];
            foreach ($last7monthsTimestamp as $month) {
                $monthName = \Carbon\Carbon::parse($month . '-01')->format('M');
                $booksCount = DB::table('book_user')
                    ->whereRaw('DATE_FORMAT(borrowed_at, "%Y-%m") = ?', [$month])
                    ->count();
                $borrowedBooks[] = [
                    "month" => $monthName,
                    "books" => $booksCount,
                ];
            }
            return $this->successResponse($borrowedBooks);
        });
    }
}
