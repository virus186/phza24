<?php

namespace App\Traits;


use Freshbitsweb\LaravelGoogleAnalytics4MeasurementProtocol\Facades\GA4;
use Exception;



trait GoogleAnalytics4
{
    public function postEvent($data)
    {
        try{
            if(session()->get(config('google-analytics-4-measurement-protocol.client_id_session_key'))){
                $test = GA4::postEvent($data);
                return $test;
            }
            return true;
        }catch(Exception $e){
            return false;
        }
    }
}
