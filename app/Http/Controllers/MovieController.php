<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Movie;

class MovieController extends Controller
{
    // Start your code here, before you start don't forget to pray
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('OMDB_API_KEY');
    }

    public function index(Request $request)
    {
        $searchQuery = $request->get('search', 'movie');
        $page = $request->get('page', 1); // Page number for pagination
        
        try {
            // Request data from OMDb API
            $response = $this->client->get('https://www.omdbapi.com/', [
                'query' => [
                    's' => $searchQuery,
                    'page' => $page,
                    'apikey' => $this->apiKey,
                ]
            ]);
        
            // Decode the JSON response and fetch the movies
            $allMovies = json_decode($response->getBody()->getContents(), true)['Search'] ?? [];
        
            // Shuffle movies and select 12 per page
            shuffle($allMovies);
            $movies = array_slice($allMovies, 0, 12);
        
            // Return movies as JSON for infinite scroll
            if ($request->ajax()) {
                return response()->json([
                    'movies' => $movies,
                    'next_page' => $page + 1 // For infinite scroll
                ]);
            }
        
            // Render the page with the movies
            return view('movies.index', compact('movies', 'searchQuery'));
        
        } catch (\Exception $e) {
            // In case of an error
            return response()->json(['error' => 'Unable to fetch movies. Please try again later.'], 500);
        }
    }

    public function show($id)
    {
        $response = $this->client->get('https://www.omdbapi.com/', [
            'query' => [
                'i' => $id,
                'apikey' => $this->apiKey,
            ]
        ]);
        $movie = json_decode($response->getBody()->getContents(), true);

        return view('movies.show', compact('movie'));
    }
}
