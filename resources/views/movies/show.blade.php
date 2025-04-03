{{-- resources/views/movies/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="movie-detail">
        <!-- Gambar film -->
        <img src="{{ $movie['Poster'] }}" alt="{{ $movie['Title'] }}" style="width: 300px; height: auto;">
        
        <h1>{{ $movie['Title'] }}</h1>
        <p><strong>Released:</strong> {{ $movie['Released'] }}</p>
        <p><strong>Genre:</strong> {{ $movie['Genre'] }}</p>
        <p><strong>Director:</strong> {{ $movie['Director'] }}</p>
        <p><strong>Plot:</strong> {{ $movie['Plot'] }}</p>

        <!-- Tombol untuk menambahkan ke favorit -->
        <form method="POST" action="{{ route('favorites.store') }}">
            @csrf
            <input type="hidden" name="movie_id" value="{{ $movie['imdbID'] }}">
            <button type="submit">Add to Favorites</button>
        </form>
    </div>
@endsection
