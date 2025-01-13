<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $generes = Genre::all();

        return response()->json([
            'status' => 'success',
            'data' => $generes,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Create a new book
        $genere = Genre::create($validated);

        return response()->json([
            "status" => "success",
            'data' => $genere,
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        try {
            $genre = Genre::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $genre,
            ],200);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $genre = Genre::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $genre->update($validated);

            return response()->json([
                'status' => 'success',
                'data' => $genre,
            ],200);
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
            $genre = Genre::findOrFail($id);
            $genre->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Genre deleted successfully',
            ],200);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
