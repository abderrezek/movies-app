<?php

namespace App\View\Components;

use Carbon\Carbon;
use Illuminate\View\Component;

class MovieCard extends Component
{
    public $movie;
    public $genres;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($movie, $genres)
    {
        $this->movie = $movie;
        $this->genres = $genres;
    }

    public function posterPath()
    {
        return "https://image.tmdb.org/t/p/w500/{$this->movie['poster_path']}";
    }

    public function voteAverage()
    {
        return $this->movie['vote_average'] * 10 . '%';
    }

    public function releaseDate()
    {
        return Carbon::parse($this->movie['release_date'])->format('M d, Y');
    }

    public function genres()
    {
        return collect($this->movie['genre_ids'])
            ->mapWithKeys(fn ($value) => [$value => $this->genres->get($value)])
            ->implode(', ');
    }

    public function pathMovie()
    {
        return route('movies.show', ['id' => $this->movie['id']]);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.movie-card');
    }
}
