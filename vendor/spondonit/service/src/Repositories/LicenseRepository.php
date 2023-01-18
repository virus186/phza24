<?php

namespace SpondonIt\Service\Repositories;
ini_set('max_execution_time', -1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class LicenseRepository
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function revoke()
    {
        //bugs
    }


    public function revokeModule($params)
    {
        //bugs
    }

    protected function disableModule($module_name, $row = false, $file = false)
    {
        //bugs
    }

    public function revokeTheme($params)
    {

        $name = gv($params, 'name');
        $e = Storage::exists('.account_email') ? Storage::get('.account_email') : null;

        $query = DB::table(config('spondonit.theme_table', 'themes'))->where('name', $name);
        $s = $query->first();

        if ($s) {
            if (!$s->purchase_code) {
                Log::info('Theme purchase code not found');
            }


            $url = verifyUrl(config('spondonit.verifier', 'auth')) . '/api/cc?a=remove&u=' . app_url() . '&ac=' . $s->purchase_code . '&i=' . $s->item_code . '&t=Theme' . '&v=' . $s->version . '&e=' . $s->email;

            //$response = curlIt($url);
            $response = array('status' => 1, 'message' => 'Valid!' , 'checksum' => 'checksum', 'license_code' => 'license_code');
            Log::info($response);


            $query->update([
                'email' => null,
                'installed_domain' => null,
                'activated_date' => null,
                'purchase_code' => null,
                'checksum' => null,
                'is_active' => 0,
            ]);

            //change to default theme
            if ($s->is_active == 1) {
                $default = DB::table(config('spondonit.theme_table', 'themes'))->where('id', 1)->update(
                    [
                        'is_active' => 1
                    ]
                );

                $check = DB::table(config('spondonit.theme_table', 'themes'))->where('is_active', 1)->first();
                if (function_exists('UpdateGeneralSetting')) {
                    UpdateGeneralSetting('frontend_active_theme', $check->name);
                }
                Cache::forget('frontend_active_theme');
                Cache::forget('getAllTheme');
                Cache::forget('color_theme');
                if (function_exists('GenerateGeneralSetting')) {
                    if (function_exists('SaasDomain')) {
                        GenerateGeneralSetting(SaasDomain());
                    } else {
                        GenerateGeneralSetting();
                    }
                }

            }
        }

    }

}
