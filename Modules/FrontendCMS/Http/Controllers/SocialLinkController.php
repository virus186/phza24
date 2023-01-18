<?php

namespace Modules\FrontendCMS\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shipping\Repositories\ShippingRepository;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Session;
use Modules\UserActivityLog\Traits\LogActivity;
use Modules\FrontendCMS\Repositories\SocialLinkResitory;

class SocialLinkController extends Controller
{
    protected $seller;

    public function __construct(SocialLinkResitory $seller)
    {
        $this->middleware('maintenance_mode');
        $this->seller = $seller;
    }

    public function social_Link()
    {
        $id = getParentSellerId();
        if(isset($id)){
            $data['socialLinks'] = $this->seller->getSocialLink($id);
            return view('frontendcms::social_link.index',$data);
        }
    }

    public function socialLinkStore(Request $request){

        $request->validate([
            'url' => 'required|max:255',
            'icon' => 'required|max:255',
            'status' => 'required|max:255'
        ]);
        $this->seller->SaveSocilaLink($request->except('_token'));
        LogActivity::successLog('Social link store successful.');
        return $this->loadSocialList();

    }
    public function socialLinkUpdate(Request $request){
        $request->validate([
            'url' => 'required|max:255',
            'icon' => 'required|max:255',
            'status' => 'required|max:255'
        ]);
        $this->seller->UpdateSocilaLink($request->except('_token'),$request->id);
        LogActivity::successLog('Social link update successful.');
        return $this->loadSocialList();
    }
    public function socialLinkDelete(Request $request){
        try {
            $this->seller->linkById($request->id);
            LogActivity::successLog('Social link delete successful.');
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ]);
        }

        return $this->loadSocialList();
    }

    private function loadSocialList()
    {
        try {
            $id  =Auth::user()->id;
            $socialLinks = $this->seller->getSocialLink($id);

            return response()->json([
                'TableData' =>  (string)view('frontendcms::social_link.components.social_link', compact('socialLinks'))
            ],200);

        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }


}

