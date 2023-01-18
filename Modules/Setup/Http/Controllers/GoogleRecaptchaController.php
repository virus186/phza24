<?php

namespace Modules\Setup\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GoogleRecaptchaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('setup::recaptcha.index');
    }

   
    public function update(Request $request)
    {
         if($request->ajax())
        {
             if ($request->login_value == 1) {
                $login_value ="true";
            }else{
                $login_value ="false";
            }
             if ($request->register_value == 1) {
                $register_value ="true";
            }else{
                $register_value ="false";
            }
             if ($request->contact_value == 1) {
                $contact_value ="true";
            }else{
                $contact_value ="false";
            }
             if ($request->checkout_value == 1) {
                $checkout_value ="true";
            }else{
                $checkout_value ="false";
            }
             if ($request->email_value == 1) {
                $email_value ="true";
            }else{
                $email_value ="false";
            }
             if ($request->nocaptcha_invisible == 1) {
                $nocaptcha_invisible ="true";
            }else if ($request->nocaptcha_invisible == 0) {
                $nocaptcha_invisible ="false";
            }
            $env = file_get_contents(base_path() . '/.env');
            $env = explode("\n", $env);
            $data = array("NOCAPTCHA_SITEKEY"=>$request->captcha_sitekey,"NOCAPTCHA_SECRET"=>$request->captcha_secret, "NOCAPTCHA_FOR_LOGIN"=>$login_value,"NOCAPTCHA_FOR_REG"=>$register_value,"NOCAPTCHA_FOR_CONTACT"=>$contact_value,"NOCAPTCHA_FOR_CHECKOUT"=>$checkout_value,"NOCAPTCHA_FOR_EMAIL"=>$email_value,"NOCAPTCHA_VERSION"=>$request->nocaptcha_version,"NOCAPTCHA_INVISIBLE"=>$nocaptcha_invisible);
            foreach ((array)$data as $key => $value) {
                putEnvConfigration($key, $value);   
            }
            return true;
        }
    }

}
