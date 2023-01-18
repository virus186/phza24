<?php

namespace Modules\Shipping\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShippingRequest extends FormRequest
{

    public function rules()
    {
        return [
            'method_name' => ['required','max:255',Rule::unique('shipping_methods', 'method_name')->where(function($q){
                $seller_id = getParentSellerId();
                return $q->where('id', '!=', $this->id)->where('request_by_user', $seller_id);
            })],
            'carrier_id' => 'nullable',
            'cost_based_on' => 'required',
            'cost' => 'required',
            'method_logo' => 'nullable|mimes:jpg,jpeg,bmp,png'
        ];
    }


    public function authorize()
    {
        return true;
    }
}
