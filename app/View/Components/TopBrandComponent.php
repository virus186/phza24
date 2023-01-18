<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Modules\FrontendCMS\Entities\HomePageSection;

class TopBrandComponent extends Component
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
        $top_brands = HomePageSection::where('section_name','top_brands')->first();
        return view(theme('components.top-brand-component'),compact('top_brands'));
    }
}
