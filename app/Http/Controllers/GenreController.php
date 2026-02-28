<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GenreController extends Controller
{
    public function index(): View
    {
        $genres = Genre::query()->paginate(10);
        $trashedGenres = Genre::onlyTrashed()->paginate(10);

        return view('genres.index', ['genres' => $genres, 'trashedGenres' => $trashedGenres]);
    }
    public function edit(Genre $genre): View
    {
        return view('genres.edit', [
            'genre' => $genre,
        ]);
    }

    public function destroy(Genre $genre): RedirectResponse
    {
        $genre->delete();

        return redirect()->route('genres.index')->with('success', 'Genre deleted.');
    }

    public function restore(string $genreId): RedirectResponse
    {
        Genre::withTrashed()->where('id', $genreId)->firstOrFail()->restore();

        return redirect()->route('genres.index')->with('success', 'Genre restored.');
    }
}
