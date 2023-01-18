<?php

namespace Modules\PaymentGateway\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\PaymentGateway\Services\PaymentGatewayService;
use Modules\UserActivityLog\Traits\LogActivity;

class PaymentGatewaySettingController extends Controller
{
    protected $paymentGatewayService;

    public function __construct(PaymentGatewayService  $paymentGatewayService)
    {
        $this->middleware('maintenance_mode');
        $this->paymentGatewayService = $paymentGatewayService;
    }

    public function index(){
        if(!isModuleActive('MultiVendor')){
            abort(404);
        }
        $data['gateway_activations'] = $this->paymentGatewayService->gateway_activations();
        if(!isModuleActive('Bkash')){
            $data['gateway_activations'] =  $data['gateway_activations']->filter(function($item) {
                if($item->method != 'Bkash'){
                    return $item->id;
                }
            });
        }

        if(!isModuleActive('SslCommerz')){
            $data['gateway_activations'] =  $data['gateway_activations']->filter(function($item) {
                if($item->method != 'SslCommerz'){
                    return $item->id;
                }
            });
        }

        if(!isModuleActive('MercadoPago')){
            $data['gateway_activations'] =  $data['gateway_activations']->filter(function($item) {
                if($item->method != 'Mercado Pago'){
                    return $item->id;
                }
            });
        }
        return view('paymentgateway::setting', $data);
    }

    public function update(Request $request){
        $request->validate([
            'status' => 'required'
        ]);
        $result = $this->paymentGatewayService->update($request->status);
        if($result){
            return response()->json([
                'msg' => 'success'
            ],200);
        }
        return response()->json([
            'msg' => 'error'
        ],500);
    }

    public function activation(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        try {
            $this->paymentGatewayService->global_setting_activation($request->only('id', 'status'));
            $data['gateway_activations'] = $this->paymentGatewayService->gateway_activations();
            LogActivity::successLog('payment activate successful.');
            return response()->json([
                'status' => 1
            ]);
        }catch(\Exception $e){

            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status' => 0
            ]);
        }
    }
}
