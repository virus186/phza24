<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RandomAdsComponent extends Component
{
    
    public function __construct()
    {
        //
    }

    
    public function render()
    {
        $ads = \Modules\FrontendCMS\Entities\SubscribeContent::find(4);
        return view(theme('components.random-ads-component'),compact('ads'));
    }
}
