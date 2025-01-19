<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse(Book::all());
    }

    /**
     * Standardized success response.
     */
    private function successResponse($data, $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookStoreRequest $request)
    {
        return $this->handleRequest(function () use ($request) {
            $validated = $request->validated();
            $validated['cover_image'] = $this->uploadImage($validated['cover_image']);
            $book = Book::create($validated);
            return $this->successResponse($book, 201);
        });
    }

    /**
     * Handle repetitive request handling logic.
     */
    private function handleRequest(callable $callback)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Upload image to Cloudinary.
     */
    private function uploadImage($image, $oldImage = null)
    {
        if (is_string($image)) {
            return $image;
        }

        if ($oldImage) {
            $this->deleteOldImage($oldImage);
        }

        return Cloudinary::upload($image->getRealPath(), [
            'folder' => "books/" . date("Y") . "/" . date("M"),
        ])->getSecurePath();
    }

    /**
     * Delete the old image from Cloudinary.
     */
    private function deleteOldImage($oldImage)
    {
        $publicId = $this->getPublicIdFromUrl($oldImage);
        if ($publicId) {
            Cloudinary::destroy($publicId);
        }
    }

    /**
     * Extract public ID from Cloudinary URL.
     */
    private function getPublicIdFromUrl(string $url): ?string
    {
        $parts = explode('/', $url);
        return explode('.', join('/', array_slice($parts, 7)))[0];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return $this->handleRequest(function () use ($id) {
            $book = Book::findOrFail($id);
            $book->delete();
            return $this->successResponse(['message' => 'Book deleted successfully']);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->handleRequest(function () use ($id) {
            $book = Book::findOrFail($id);
            return $this->successResponse($book);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdateRequest $request, $id)
    {
        return $this->handleRequest(function () use ($request, $id) {
            $validated = $request->validated();
            $book = Book::findOrFail($id);
            $validated['cover_image'] = $this->uploadImage($validated['cover_image'], $book->cover_image);
            $book->update($validated);
            return $this->successResponse($book);
        });
    }
}
