<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Modules\FrontendCMS\Entities\SellerSocialLink;

class SubscribeComponent extends Component
{
    public $subscribeContent;
    public function __construct($subscribeContent)
    {
        $this->subscribeContent = $subscribeContent;
    }

    
    public function render()
    {
        $sellerSocialLinks =SellerSocialLink::where('user_id',1)->where('status',1)->orderBy('id','desc')->get();
        return view(theme('components.subscribe-component'),compact('sellerSocialLinks'));
    }
}
