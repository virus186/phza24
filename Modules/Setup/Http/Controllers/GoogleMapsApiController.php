<?php

namespace Modules\Setup\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\UserActivityLog\Traits\LogActivity;
use Exception;

class GoogleMapsApiController extends Controller
{
    public function index()
    {
        try{
            // $analytics = $this->analyticsService->getAnalytics();
            // $businessData = $this->analyticsService->getBusinessData();

            return view('setup::maps.index');
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }

    public function update(Request $request)
    {
        if($request->ajax())
        {
            if ($request->status_value == 1) {
                $status ="true";
            }else{
                $status ="false";
            }
            $data = array("GOOGLE_MAP_KEY"=>$request->api_key, "GOOGLE_MAPS_STATUS"=>$status,"GOOGLE_MAPS_COUNTRY_1"=>$request->country_1,"GOOGLE_MAPS_COUNTRY_2"=>$request->country_2,"GOOGLE_MAPS_COUNTRY_3"=>$request->country_3,"GOOGLE_MAPS_COUNTRY_4"=>$request->country_4,"GOOGLE_MAPS_COUNTRY_5"=>$request->country_5,);
            foreach ((array)$data as $key => $value) {
                putEnvConfigration($key, $value);
            }
            return true;
        }
    }

}
