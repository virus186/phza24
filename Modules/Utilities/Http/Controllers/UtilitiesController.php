<?php

namespace Modules\Utilities\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\UserActivityLog\Traits\LogActivity;
use Modules\Utilities\Repositories\UtilitiesRepository;

class UtilitiesController extends Controller
{
    protected $utilitiesRepository;
    public function __construct(UtilitiesRepository $utilitiesRepository)
    {
        $this->middleware('maintenance_mode');
        $this->utilitiesRepository = $utilitiesRepository;
    }

    public function index(Request $request)
    {
        
        try{
            if($request->has('utilities') && $request->get('utilities')!=null){
                if(env('APP_SYNC')){
                    Toastr::error(__('common.restricted_in_demo_mode'));
                    return redirect()->back();
                }
                if($request->utilities == 'xml_sitemap'){
                    return redirect()->route('utilities.xml_sitemap');
                }
                $result = $this->utilitiesRepository->updateUtility($request->utilities);
                if($result == 'done'){
                    Toastr::success(__('common.operation_done_successfully'), __('common.success'));
                    LogActivity::successLog('Utility Operation Done.');
                }else{
                    Toastr::error(__('common.error_message'),__('common.error'));
                }
                return redirect()->back();

            }else{
                $sitemap_config = $this->utilitiesRepository->getSitemapConfig();
                return view('utilities::index', compact('sitemap_config'));
            }
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.error_message'), __('common.error'));
            return redirect()->back();
        }
    }

    public function xml_sitemap(Request $request){
        if($request->sitemap){
            $data = $this->utilitiesRepository->get_xml_data($request);
            if($data){
                return redirect(route('utilities.xml_sitemap_public'));
            }else{
                Toastr::error(__('utilities.choose_sitemap_option'), __('common.error'));
                return back();
            }
        }else{
            Toastr::error(__('utilities.choose_sitemap_option'), __('common.error'));
            return back();
        }
    }

    public function xml_sitemap_public(){
        $data = $this->utilitiesRepository->xml_sitemap_public();
        return response()->view('utilities::xml_sitemap', $data)->header('Content-Type', 'text/xml');
    }

    public function reset_database(Request $request)
    {
        
        DB::beginTransaction();

        try { 
           
            if ($request->password == ""){
                Toastr::error(__('common.enter_your_password'));
               
            }
            elseif (Hash::check($request->password, auth()->user()->password)) {
                
                $this->utilitiesRepository->reset_database($request);
                DB::commit();
                Toastr::success(__('utilities.database_reset_successful'));
            }else{
                
                Toastr::error(__('common.invalid_password'));
            }
             
            return back();
        }catch(Exception $e){
            DB::rollBack();
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.error_message'), __('common.error'));
            return redirect()->back();
        }
    }

    public function import_demo_database(Request $request){
        DB::beginTransaction();
        try{
            if ($request->password == ""){
                Toastr::error(__('common.enter_your_password'));
            }
            elseif (Hash::check($request->password, auth()->user()->password)) {
                $this->utilitiesRepository->import_demo_database($request->except('_token'));
                DB::commit();
                Toastr::success(__('utilities.import_demo_database_successful'));
            }else{
                Toastr::error(__('common.invalid_password'));
            }
            return redirect()->back();

        }catch(Exception $e){
            DB::rollBack();
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.error_message'), __('common.error'));
            return redirect()->back();
        }
    }

    public function remove_Visitor(Request $request){
       
        try{ 
            if ($request->password == ""){
                Toastr::error(__('common.enter_your_password'));
            }
            elseif (Hash::check($request->password, auth()->user()->password)) {
                $this->utilitiesRepository->remove_visitor($request->except('_token'));
                Toastr::success(__('utilities.remove_visitor_successful'));
            }else{
                Toastr::error(__('common.invalid_password'));
            }
            return redirect()->back();
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.error_message'), __('common.error'));
            return redirect()->back();
        }
    }

}
