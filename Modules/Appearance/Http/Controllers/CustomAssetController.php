<?php

namespace Modules\Appearance\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Appearance\Repositories\CustomAssetRepository;
use Modules\UserActivityLog\Traits\LogActivity;

class CustomAssetController extends Controller
{
    protected $customAssetRepo;
    public function __construct(CustomAssetRepository $customAssetRepo)
    {
        $this->customAssetRepo = $customAssetRepo;
    }

    public function index()
    {
        $data = $this->customAssetRepo->getFileContent();
        return view('appearance::custom_asset.index', $data);
    }

    public function store(Request $request){
        
        try{
            $this->customAssetRepo->updateCustomFile($request->only(['custom_css','custom_js']));
            Toastr::success(__('common.updated_successfully'), __('common.success'));
            LogActivity::successLog('Custom ssset updated successfully!');
            return redirect()->back();
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.error_message'), __('common.error'));
            return back();
        }
    }    
}
