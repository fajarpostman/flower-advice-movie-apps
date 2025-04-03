@extends('layouts.app')

@section('title', 'Movie List')

@section('content')
<div class="container">
    <h2 class="mb-4">Movie List</h2>

    <!-- Search Form -->
    <form method="GET" action="{{ route('movies.index') }}" class="mb-3">
        <input type="text" name="search" placeholder="Search movie..." class="form-control" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary mt-2">Search</button>
    </form>

    <!-- Movie List -->
    <div id="movie-list" class="row">
        @forelse ($movies as $movie)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <!-- Movie Poster with lazy loading -->
                    <img 
                        data-src="{{ $movie['Poster'] }}" 
                        alt="{{ $movie['Title'] }}" 
                        class="card-img-top lazy-load" 
                        style="width: 100%; height: auto;">
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $movie['Title'] }}</h5>
                        <p class="card-text">Year: {{ $movie['Year'] }}</p>
                        <a href="{{ route('movies.show', ['id' => $movie['imdbID']]) }}" class="btn btn-info">Details</a>

                        <!-- Add to Favorites Button -->
                        <form method="POST" action="{{ route('favorites.store') }}" class="mt-2">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $movie['imdbID'] }}">
                            <button type="submit" class="btn btn-warning">Add to Favorites</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No movies found.</p>
        @endforelse
    </div>

    <!-- Loading Placeholder for Infinite Scroll -->
    <div id="loading" style="display:none;" class="text-center mt-3">
        <p>Loading...</p>
    </div>
</div>

<!-- Infinite Scroll -->
<script>
    let page = 1;
    let loading = false;

    // Infinite scroll event
    window.onscroll = function() {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
            loadMoreMovies();
        }
    };

    // Load more movies as the user scrolls
    function loadMoreMovies() {
        if (loading) return;
        loading = true;
        document.getElementById('loading').style.display = 'block';

        page++; // Increment page number for the next fetch

        // Fetch new movies from the server with the current page and search query
        fetch('/movies?page=' + page + '&search={{ request('search') }}') // Pass search query
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                loading = false;

                let movieList = document.getElementById('movie-list');
                data.movies.forEach(movie => {
                    let colDiv = document.createElement('div');
                    colDiv.classList.add('col-md-4', 'mb-3');

                    let cardDiv = document.createElement('div');
                    cardDiv.classList.add('card');

                    let movieImg = document.createElement('img');
                    movieImg.setAttribute('data-src', movie.Poster);
                    movieImg.classList.add('card-img-top', 'lazy-load');
                    movieImg.style.width = "100%";
                    movieImg.style.height = "auto";
                    cardDiv.appendChild(movieImg);

                    let cardBody = document.createElement('div');
                    cardBody.classList.add('card-body');

                    let movieTitle = document.createElement('h5');
                    movieTitle.classList.add('card-title');
                    movieTitle.innerText = movie.Title;
                    cardBody.appendChild(movieTitle);

                    let movieYear = document.createElement('p');
                    movieYear.classList.add('card-text');
                    movieYear.innerText = `Year: ${movie.Year}`;
                    cardBody.appendChild(movieYear);

                    let movieLink = document.createElement('a');
                    movieLink.setAttribute('href', `/movies/${movie.imdbID}`);
                    movieLink.classList.add('btn', 'btn-info');
                    movieLink.innerText = "Details";
                    cardBody.appendChild(movieLink);

                    let favoriteForm = document.createElement('form');
                    favoriteForm.setAttribute('method', 'POST');
                    favoriteForm.setAttribute('action', '/favorites/store');
                    favoriteForm.classList.add('mt-2');
                    favoriteForm.innerHTML = `
                        @csrf
                        <input type="hidden" name="movie_id" value="${movie.imdbID}">
                        <button type="submit" class="btn btn-warning">Add to Favorites</button>
                    `;
                    cardBody.appendChild(favoriteForm);

                    cardDiv.appendChild(cardBody);
                    colDiv.appendChild(cardDiv);
                    movieList.appendChild(colDiv);
                });
            })
            .catch(error => {
                console.error('Error loading more movies:', error);
            });
    }

    // Lazy Load initialization
    document.addEventListener("DOMContentLoaded", function() {
        const lazyImages = document.querySelectorAll('.lazy-load');

        function lazyLoad() {
            lazyImages.forEach(image => {
                if (image.offsetTop < window.innerHeight + window.scrollY) {
                    image.src = image.getAttribute('data-src');
                    image.classList.remove('lazy-load');
                }
            });
        }

        lazyLoad(); // Initial load
        window.addEventListener('scroll', lazyLoad); // Lazy load on scroll
    });
</script>
@endsection
