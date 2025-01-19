<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use App\Services\ImageService;

class BookController extends Controller
{
    public function index()
    {
        return $this->successResponse(Book::all());
    }

    public function store(BookStoreRequest $request, ImageService $imageService)
    {
        return $this->handleRequest(function () use ($imageService, $request) {
            $validated = $request->validated();
            $validated['cover_image'] = $imageService->uploadImage($validated['cover_image']);
            $book = Book::create($validated);
            return $this->successResponse($book, 201);
        });
    }

    public function destroy($id)
    {
        return $this->handleRequest(function () use ($id) {
            $book = Book::findOrFail($id);
            $book->delete();
            return $this->successResponse(['message' => 'Book deleted successfully']);
        });
    }

    public function show($id)
    {
        return $this->handleRequest(function () use ($id) {
            $book = Book::findOrFail($id);
            return $this->successResponse($book);
        });
    }

    public function update(BookUpdateRequest $request, $id, ImageService $imageService)
    {
        return $this->handleRequest(function () use ($request, $id, $imageService) {
            $validated = $request->validated();
            $book = Book::findOrFail($id);
            $validated['cover_image'] = $imageService->uploadImage($validated['cover_image'], $book->cover_image);
            $book->update($validated);
            return $this->successResponse($book);
        });
    }
}
