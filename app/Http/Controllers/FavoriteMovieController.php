<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FavoriteMovie;

class FavoriteMovieController extends Controller
{
    // Start your code here, before you start don't forget to pray
    public function store(Request $request)
    {
        $favorite = new FavoriteMovie();
        $favorite->movie_id = (string) $request->movie_id;
        $favorite->user_id = auth()->id();
        $favorite->save();

        return redirect()->route('movies.index');
    }

    public function destroy($id)
    {
        $favorite = FavoriteMovie::find($id);
        $favorite->delete();

        return redirect()->route('favorite.index');
    }

    public function index()
    {
        $favorites = FavoriteMovie::where('user_id', auth()->id())->get();
        return view('favorite.index', compact('favorites'));
    }
}
