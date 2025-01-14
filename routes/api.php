<?php

use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('api.register');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\AuthController::class, 'getUser']);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('/borrow', [\App\Http\Controllers\BorrowingController::class, 'borrow'])->name('api.borrow');
    Route::post('/return', [\App\Http\Controllers\BorrowingController::class, 'backup'])->name('api.return');
    Route::get('/my-books', [\App\Http\Controllers\BorrowingController::class, 'borrowedBooks']);
    Route::apiResource('/reviews', \App\Http\Controllers\ReviewController::class);
});

Route::middleware(\App\Http\Middleware\AuthRoleBased::class)->group(function () {
    Route::apiResource('/genres', \App\Http\Controllers\GenreController::class);
    Route::apiResource('/books', \App\Http\Controllers\BookController::class)
        ->middleware(\App\Http\Middleware\RandomIsbnGenerator::class)->names('api.books');
    Route::get('/analytics', [\App\Http\Controllers\BorrowingController::class, 'analyticsBooksBorrowed']);
    Route::get('admin/reviews', [\App\Http\Controllers\ReviewController::class, 'adminReview']);
});
