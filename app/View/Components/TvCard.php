<?php

namespace App\View\Components;

use Carbon\Carbon;
use Illuminate\View\Component;

class TvCard extends Component
{
    public $tvshow;
    public $genres;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($tvshow, $genres)
    {
        $this->tvshow = $tvshow;
        $this->genres = $genres;
    }

    public function posterPath()
    {
        return 'https://image.tmdb.org/t/p/w500/'.$this->tvshow['poster_path'];
    }

    public function voteAverage()
    {
        return $this->tvshow['vote_average'] * 10 .'%';
    }

    public function firstAirDate()
    {
        return Carbon::parse($this->tvshow['first_air_date'])->format('M d, Y');
    }

    public function genres()
    {
        return collect($this->genres)->mapWithKeys(fn ($genre) => [$genre['id'] => $genre['name']]);
    }

    public function genresFormatted()
    {
        return collect($this->tvshow['genre_ids'])->mapWithKeys(
            fn ($value) => [$value => $this->genres()->get($value)]
        )->implode(', ');
    }

    public function patheRoute()
    {
        return route('tv.show', $this->tvshow['id']);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tv-card');
    }
}
