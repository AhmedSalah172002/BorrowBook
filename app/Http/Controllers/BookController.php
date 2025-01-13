<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();

        return response()->json([
            "status" => "success",
            'data' => $books,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookStoreRequest $request)
    {
        try {

            $validated = $request->validated();

            // Create a new book
            $book = Book::create($validated);

            return response()->json([
                "status" => "success",
                'data' => $book,
            ],201);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $book = Book::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $book,
            ],200);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookStoreRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $book = Book::findOrFail($id);

            if ($book) {
                $book->update($validated);
                return response()->json([
                    'status' => 'success',
                    'data' => $book,
                ],200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Book not found',
            ]);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Book deleted successfully' ,
            ],200);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
