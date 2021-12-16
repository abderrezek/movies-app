<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SearchDropdown extends Component
{
    public $search = '';

    public function render()
    {
        $searchResults = [];

        if (strlen($this->search) >= 2) {
            $searchResults = Http::get('https://api.themoviedb.org/3/search/movie?query='.$this->search.'&api_key=ad743a44c016c7e6ff119e39249d2927')
                ->json()['results'];
        }

        return view('livewire.search-dropdown', [
            'searchResults' => collect($searchResults)->take(7),
        ]);
    }
}
