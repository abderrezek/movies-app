<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = config('services.tmbd.token');
        $apiUrl = config('services.tmbd.url');

        $popularMovies = Http::withToken($token)
            ->get("{$apiUrl}/movie/popular")
            ->json()['results'];

        $nowPlayingMovies = Http::withToken($token)
            ->get("{$apiUrl}/movie/now_playing")
            ->json()['results'];

        $genresArray = Http::withToken($token)
            ->get("{$apiUrl}/genre/movie/list")
            ->json()['genres'];

        $genres = collect($genresArray)->mapWithKeys(fn ($genre) => [$genre['id'] => $genre['name']]);

        return view('movies.index', [
            'popularMovies' => $popularMovies,
            'nowPlayingMovies' => $nowPlayingMovies,
            'genres' => $genres,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $token = 'ad743a44c016c7e6ff119e39249d2927';
        $apiUrl = config('services.tmbd.url');
        $movie = Http::get("{$apiUrl}/movie/{$id}?api_key={$token}&append_to_response=credits,videos,images")
            ->json();

        $poster_path = $movie['poster_path']
            ? 'https://image.tmdb.org/t/p/w500/'.$movie['poster_path']
            : 'https://via.placeholder.com/500x750';
        $vote_average = $movie['vote_average'] * 10 .'%';
        $release_date = Carbon::parse($movie['release_date'])->format('M d, Y');
        $genres = collect($movie['genres'])->pluck('name')->flatten()->implode(', ');
        $crews = collect($movie['credits']['crew'])->take(2);
        $casts = collect($movie['credits']['cast'])->take(5)->map(function($cast) {
            return collect($cast)->merge([
                'profile_path' => $cast['profile_path']
                    ? 'https://image.tmdb.org/t/p/w300'.$cast['profile_path']
                    : 'https://via.placeholder.com/300x450',
            ]);
        });
        $images = collect($movie['images']['backdrops'])->take(9);

        // dump($movie);

        return view('movies.show', [
            'movie' => $movie,
            'poster_path' => $poster_path,
            'vote_average' => $vote_average,
            'release_date' => $release_date,
            'genres' => $genres,
            'crews' => $crews,
            'casts' => $casts,
            'images' => $images,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
