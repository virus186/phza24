<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Setup\Entities\City;
use Modules\Setup\Entities\Country;
use Modules\Setup\Entities\State;

class OrderAddressDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getBillingCountry(){
        return $this->belongsTo(Country::class,'billing_country_id','id');
    }

    public function getBillingState(){
        return $this->belongsTo(State::class,'billing_state_id','id');
    }

    public function getBillingCity(){
        return $this->belongsTo(City::class,'billing_city_id','id');
    }

    public function getShippingCountry(){
        return $this->belongsTo(Country::class,'shipping_country_id','id');
    }

    public function getShippingState(){
        return $this->belongsTo(State::class,'shipping_state_id','id');
    }

    public function getShippingCity(){
        return $this->belongsTo(City::class,'shipping_city_id','id');
    }
}
