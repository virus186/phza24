<?php

namespace App\View\Components;

use App\Models\SearchTerm;
use Illuminate\View\Component;

class PopularSearchComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $search_items = SearchTerm::latest()->take(30)->get();
        return view(theme('components.popular-search-component'),compact('search_items'));
    }
}
