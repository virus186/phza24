<?php
namespace Modules\FrontendCMS\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\FrontendCMS\Entities\SellerSocialLink;

class SocialLinkResitory {

    protected $seller;
    protected $socialLink;

    public function __construct(User $seller, SellerSocialLink $socialLink)
    {
        $this->seller = $seller;
        $this->socialLink = $socialLink;
    }
    public function getSocialLink($id){
        return $this->socialLink::where('user_id',$id)->get();
    }


    public function SaveSocilaLink($data){
        return $this->socialLink::create([
            'url' => $data['url'],
            'icon' => $data['icon'],
            'status' => $data['status'],
            'user_id' => Auth::user()->id
        ]);
    }

    public function UpdateSocilaLink($data, $id){

        return $this->socialLink::where('id',$id)->update([
            'url' => $data['url'],
            'icon' => $data['icon'],
            'status' => $data['status']
        ]);
    }
    public function linkById($id){
        $data =  $this->socialLink::where('id',$id)->firstOrFail();
        return $data->delete();
    }

}
