<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Models\Review;
use App\Services\RatingService;

class ReviewController extends Controller
{
    public function index()
    {
        return $this->handleRequest(function () {
            $user = auth()->user();
            $reviews = Review::where('user_id', $user->id)->get();
            return $this->successResponse($reviews);
        });
    }

    public function store(ReviewStoreRequest $request, RatingService $ratingService)
    {
        return $this->handleRequest(function () use ($request, $ratingService) {
            $user = auth()->user();
            $validated = $request->validated();
            $validated['user_id'] = $user->id;
            $existReview = Review::where('user_id', $user->id)
                ->where('book_id', $validated['book_id'])
                ->first();
            if ($existReview) {
                return $this->errorResponse('You have already reviewed this book.');
            }
            $reviews = Review::create($validated);
            $ratingService->avg($validated['book_id']);

            return $this->successResponse($reviews);
        });

    }

    public function update(ReviewStoreRequest $request, $id, RatingService $ratingService)
    {
        return $this->handleRequest(function () use ($request, $id, $ratingService) {
            $user = auth()->user();
            $validated = $request->validated();
            $review = Review::where('user_id', $user->id)->findOrFail($id);
            $review->update($validated);
            $ratingService->avg($validated['book_id']);
            return $this->successResponse($review);
        });
    }

    public function show($id)
    {
        return $this->handleRequest(function () use ($id) {
            $user = auth()->user();
            $review = Review::where('user_id', $user->id)->findOrFail($id);
            return $this->successResponse($review);
        });
    }

    public function destroy($id)
    {
        return $this->handleRequest(function () use ($id) {
            $user = auth()->user();
            $review = Review::where('user_id', $user->id)->findOrFail($id);
            $review->delete();
            return $this->successResponse(['message' => 'Review deleted successfully']);
        });
    }

    public function adminReview()
    {
        return $this->successResponse(Review::all());
    }
}
