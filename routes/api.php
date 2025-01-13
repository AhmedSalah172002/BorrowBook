<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('/register' , [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login' , [\App\Http\Controllers\AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::get('/profile' , [\App\Http\Controllers\AuthController::class, 'getUser']);
    Route::post('/logout' , [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('/borrow' , [\App\Http\Controllers\BorrowingController::class, 'borrow']);
    Route::post('/return' , [\App\Http\Controllers\BorrowingController::class, 'backup']);
    Route::get('/my-books' , [\App\Http\Controllers\BorrowingController::class, 'borrowedBooks']);



});

Route::middleware(\App\Http\Middleware\AuthRoleBased::class)->group(function () {
    Route::apiResource('/genres', \App\Http\Controllers\GenreController::class);
    Route::apiResource('/books', \App\Http\Controllers\BookController::class)
        ->middleware(\App\Http\Middleware\RandomIsbnGenerator::class);
    Route::get('/analytics', [\App\Http\Controllers\BorrowingController::class, 'analyticsBooksBorrowed']);


});

