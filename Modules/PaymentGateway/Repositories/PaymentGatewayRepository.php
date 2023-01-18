<?php

namespace Modules\PaymentGateway\Repositories;

use App\Traits\ImageStore;
use Modules\GeneralSetting\Entities\BusinessSetting;
use Modules\PaymentGateway\Entities\PaymentMethod;
use Illuminate\Support\Arr;
use Modules\GeneralSetting\Entities\GeneralSetting;
use Modules\PaymentGateway\Entities\SellerWisePaymentGateway;

class PaymentGatewayRepository
{
    use ImageStore;
    public function gateway_activations()
    {
        return PaymentMethod::all();
    }

    public function seller_payment_gateway(){
        if(isModuleActive('MultiVendor')){
            $methods = PaymentMethod::where('active_status', 1)->get();
        }else{
            $methods = PaymentMethod::all();
        }

        if(!isModuleActive('Bkash')){
            $methods =  $methods->filter(function($item) {
                if($item->method != 'Bkash'){
                    return $item->id;
                }
            });
        }

        if(!isModuleActive('SslCommerz')){
            $methods =  $methods->filter(function($item) {
                if($item->method != 'SslCommerz'){
                    return $item->id;
                }
            });
        }

        if(!isModuleActive('MercadoPago')){
            $methods =  $methods->filter(function($item) {
                if($item->method != 'Mercado Pago'){
                    return $item->id;
                }
            });
        }

        if(isModuleActive('MultiVendor')){
            $list = SellerWisePaymentGateway::with('method')->where('user_id', getParentSellerId())->whereHas('method', function($query){
                return $query->where('active_status', 1);
            })->get();
        }else{
            $list = SellerWisePaymentGateway::with('method')->where('user_id', getParentSellerId())->get();
        }

        if($methods->count() != $list->count()){
            foreach($methods as $method){
                $seller_method = SellerWisePaymentGateway::where('user_id', getParentSellerId())->where('payment_method_id', $method->id)->first();
                if(!$seller_method){
                    SellerWisePaymentGateway::create([
                        'payment_method_id' => $method->id,
                        'user_id' => getParentSellerId(),
                        'status' => 0
                    ]);
                }
            }
            $list = SellerWisePaymentGateway::with('method')->where('user_id', getParentSellerId())->whereHas('method', function($query){
                return $query->where('active_status', 1);
            })->get();
        }
        return $list;
    }

    public function gateway_active()
    {
        return PaymentMethod::where('active_status', 1)->get();
    }

    public function update_gateway_credentials(array $data)
    {
        $gateway = PaymentMethod::find($data['method_id']);
        if (!empty($data['logo'])) {
            $data = Arr::add($data, 'logo_src', $this->PaymentLogo($data['logo'], 36, 120));
            $this->deleteImage($gateway->logo);
        }
        if (!empty($data['logo_src'])) {
            $gateway->logo = $data['logo_src'];
        }
        $gateway->save();
        $sql = [];
        foreach ($data['types'] as $key => $type) {
            $sql['perameter_'.($key+1)] = $data[$type];
        }
        SellerWisePaymentGateway::where('id', $data['id'])->where('user_id', getParentSellerId())->update($sql);
    }

    public function global_setting_activation(array $data)
    {
        return PaymentMethod::where('id',$data['id'])->update([
            'active_status' => $data['status'],
        ]);
    }
    public function update_activation(array $data)
    {
        $seller_gateway = SellerWisePaymentGateway::where('id',$data['id'])->where('user_id',getParentSellerId())->first();
        if($seller_gateway){
            if(!isModuleActive('MultiVendor')){
                PaymentMethod::where('id',$seller_gateway->payment_method_id)->update([
                    'active_status' => $data['status']
                ]);
            }
            return SellerWisePaymentGateway::where('id',$data['id'])->where('user_id',getParentSellerId())->update([
                'status' => $data['status'],
            ]);
        }
    }

    public function overWriteEnvFile($type, $val)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"'.trim($val).'"';
            if(is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0){
                file_put_contents($path, str_replace(
                    $type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)
                ));
            }
            else{
                file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
            }
        }
    }

    public function findById($id)
    {
        return PaymentMethod::where('id', $id)->where('active_status', 1)->first();
    }

    public function update($status){
        $setting = GeneralSetting::first();
        if($setting){
            $setting->update([
                'seller_wise_payment' => $status
            ]);
            return true;
        }
        return false;
    }

}
