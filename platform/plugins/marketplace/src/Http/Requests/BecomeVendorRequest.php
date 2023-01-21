<?php

namespace Botble\Marketplace\Http\Requests;

use BaseHelper;
use Botble\Support\Http\Requests\Request;

class BecomeVendorRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shop_name' => 'required|min:2',
            'shop_phone' => 'required|' . BaseHelper::getPhoneValidationRule(),
            'shop_url' => 'required|max:200',
        ];
    }
}
