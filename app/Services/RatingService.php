<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Review;

class RatingService
{
    public function avg($bookId)
    {

        $book = Book::findOrFail($bookId);

        $reviewsStats = Review::where('book_id', $bookId)
            ->selectRaw('count(*) as count, sum(rating) as sum')
            ->first();

        if ($reviewsStats->count > 0) {
            $averageRating = $reviewsStats->sum / $reviewsStats->count;
            $averageRating = round($averageRating, 2);
        } else {
            $averageRating = 0;
        }

        $book->update([
            'average_rating' => $averageRating
        ]);
    }
}
