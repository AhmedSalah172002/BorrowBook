<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewService extends Controller
{
    public function isReviewd($bookId, $userId)
    {
        $existReview = Review::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();
        return $existReview ? true : false;
    }

}
