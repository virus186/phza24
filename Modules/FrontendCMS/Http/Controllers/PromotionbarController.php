<?php

namespace Modules\FrontendCMS\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FrontendCMS\Repositories\PromotionbarRepository;
use Modules\UserActivityLog\Traits\LogActivity;

class PromotionbarController extends Controller
{
    protected $promotionbarRepo;
    public function __construct(PromotionbarRepository $promotionbarRepo){
        $this->promotionbarRepo = $promotionbarRepo;
    }

    public function index(){
        $promotionbar = $this->promotionbarRepo->getContent();
        return view('frontendcms::promotion_bar.index', compact('promotionbar'));
    }

    public function update(Request $request)
    {
        try{
            $this->promotionbarRepo->update($request);

            Toastr::success(__('common.updated_successfully'),__('common.success'));
            LogActivity::successLog('Subscribe Content updated.');
            return 1;
            
        } catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.error_message'),__('common.error'));
            return 0;
        }

    }

    public function ads_index(){
        $ads_bar = $this->promotionbarRepo->getAdsContent();
        return view('frontendcms::ads_bar.index', compact('ads_bar'));
    }
}
