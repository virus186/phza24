<?php

namespace Modules\PaymentGateway\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerWisePaymentGateway extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    

    public function method(){
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
}
