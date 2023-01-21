<?php

namespace Botble\Marketplace\Http\Requests;

use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;
use MarketplaceHelper;

class UpdateShippingStatusRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (MarketplaceHelper::allowVendorManageShipping()) {
            return [
                'status' => Rule::in(ShippingStatusEnum::values()),
            ];
        }

        return [
            'status' => Rule::in([ShippingStatusEnum::ARRANGE_SHIPMENT, ShippingStatusEnum::READY_TO_BE_SHIPPED_OUT]),
        ];
    }
}
