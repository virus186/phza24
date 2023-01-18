<?php

namespace Modules\PaymentGateway\Entities;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $guarded = ['id'];

    public function sellerPaymentMethod(){
        return $this->hasOne(SellerWisePaymentGateway::class, 'payment_method_id', 'id')->where('user_id', session()->get('seller_for_checkout'));
    }

    public function ActivePaymentWithoutCheckout(){
        return $this->hasOne(SellerWisePaymentGateway::class, 'payment_method_id', 'id')->where('user_id', 1);
    }
}
