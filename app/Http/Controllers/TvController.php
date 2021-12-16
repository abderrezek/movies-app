<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = 'ad743a44c016c7e6ff119e39249d2927';
        $apiUrl = config('services.tmbd.url');

        $popularTv = Http::get("{$apiUrl}/tv/popular?api_key={$token}")
            ->json()['results'];

        $topRatedTv = Http::get("{$apiUrl}/tv/top_rated?api_key={$token}")
            ->json()['results'];

        $genres = Http::get("{$apiUrl}/genre/tv/list?api_key={$token}")
            ->json()['genres'];

        return view('tv.index', [
            'popularTv' => $popularTv,
            'topRatedTv' => $topRatedTv,
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
        $tvshow = Http::get("{$apiUrl}/tv/{$id}?append_to_response=credits,videos,images&api_key={$token}")
            ->json();

        $tvshow = collect($tvshow)->merge([
            'poster_path' => $tvshow['poster_path']
                ? 'https://image.tmdb.org/t/p/w500/'.$tvshow['poster_path']
                : 'https://via.placeholder.com/500x750',
            'vote_average' => $tvshow['vote_average'] * 10 .'%',
            'first_air_date' => Carbon::parse($tvshow['first_air_date'])->format('M d, Y'),
            'genres' => collect($tvshow['genres'])->pluck('name')->flatten()->implode(', '),
            'cast' => collect($tvshow['credits']['cast'])->take(5)->map(function($cast) {
                return collect($cast)->merge([
                    'profile_path' => $cast['profile_path']
                        ? 'https://image.tmdb.org/t/p/w300'.$cast['profile_path']
                        : 'https://via.placeholder.com/300x450',
                ]);
            }),
            'images' => collect($tvshow['images']['backdrops'])->take(9),
        ])->only([
            'poster_path', 'id', 'genres', 'name', 'vote_average', 'overview', 'first_air_date', 'credits' ,
            'videos', 'images', 'crew', 'cast', 'images', 'created_by'
        ]);

        return view('tv.show', [
            'tvshow' => $tvshow,
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
