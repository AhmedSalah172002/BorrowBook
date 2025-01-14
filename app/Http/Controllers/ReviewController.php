<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $user = auth()->user();

            $reviews = Review::where('user_id', $user->id)->get();

            return response()->json([
                'status' => 'success',
                'data' => $reviews,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            $validated = $request->validate([
                'book_id' => 'required|integer|exists:books,id',
                'rating' => 'required|numeric|between:0,5',
                'comment' => 'required|string|max:1000',
            ]);
            $validated['user_id'] = $user->id;
            $existReview = Review::where('user_id', $user->id)
                ->where('book_id', $validated['book_id'])
                ->first();
            if ($existReview) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have already reviewed this book.'
                ]);
            }

            $reviews = Review::create($validated);

            $this->avgRating($validated['book_id']);


            return response()->json([
                'status' => 'success',
                'data' => $reviews,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function avgRating($bookId)
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'book_id' => 'required|integer|exists:books,id',
                'rating' => 'required|numeric|between:0,5',
                'comment' => 'required|string|max:1000',
            ]);
            $user = auth()->user();
            $review = Review::where('user_id', $user->id)->findOrFail($id);
            $review->update($validated);

            $this->avgRating($validated['book_id']);

            return response()->json([
                'status' => 'success',
                'data' => $review,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = auth()->user();
            $review = Review::where('user_id', $user->id)->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $review,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = auth()->user();
            $review = Review::where('user_id', $user->id)->findOrFail($id);
            $review->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Review deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    public function adminReview()
    {
        try {
            $reviews = Review::all();
            return response()->json([
                'status' => 'success',
                'data' => $reviews,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
