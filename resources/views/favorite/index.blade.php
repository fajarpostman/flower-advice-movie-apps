@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Your Favorite Movies</h1>
        <div class="row">
            @forelse($favorites as $favorite)
                @php
                    // Make an API request to fetch movie details using IMDb ID
                    $movie = json_decode(file_get_contents("https://www.omdbapi.com/?i={$favorite->movie_id}&apikey=" . env('OMDB_API_KEY')), true);
                @endphp
                
                <div class="col-md-3">
                    <div class="card">
                        <!-- Movie Poster -->
                        <img src="{{ $movie['Poster'] }}" class="card-img-top" alt="{{ $movie['Title'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $movie['Title'] }}</h5>
                            <a href="{{ route('movies.show', $favorite->movie_id) }}" class="btn btn-primary">View Details</a>
                            
                            <!-- Option to remove from favorites -->
                            <form action="{{ route('favorites.destroy', $favorite->id) }}" method="POST" style="margin-top: 10px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Remove from Favorites</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>You don't have any favorite movies yet.</p>
            @endforelse
        </div>
    </div>
@endsection
