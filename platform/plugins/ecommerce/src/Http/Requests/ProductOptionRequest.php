<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Ecommerce\Enums\GlobalOptionEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ProductOptionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'option_name' => 'required',
            'option_type' => [
                Rule::requiredIf(function () {
                    return $this->request->get('option_type') == GlobalOptionEnum::NA;
                }),
            ],
        ];
    }
}
