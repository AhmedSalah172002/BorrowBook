<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        return $this->successResponse(Genre::all());
    }

    public function store(Request $request)
    {
        return $this->handleRequest(function () use ($request) {
            $validated = $request->validate([
                'name' => 'required|string|max:255'
            ]);
            $genre = Genre::create($validated);
            return $this->successResponse($genre, 201);
        });
    }

    public function show($id)
    {
        return $this->handleRequest(function () use ($id) {
            $genre = Genre::findOrFail($id);
            return $this->successResponse($genre);
        });
    }

    public function update(Request $request, $id)
    {

        return $this->handleRequest(function () use ($request, $id) {
            $validated = $request->validate([
                'name' => 'required|string|max:255'
            ]);
            $genre = Genre::findOrFail($id);
            $genre->update($validated);
            return $this->successResponse($genre);
        });
    }

    public function destroy($id)
    {
        return $this->handleRequest(function () use ($id) {
            $genre = Genre::findOrFail($id);
            $genre->delete();
            return $this->successResponse(['message' => 'Genre deleted successfully']);
        });
    }
}
