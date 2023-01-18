<?php

namespace Modules\Shipping\Repositories;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Modules\MultiVendor\Events\SellerShippingConfigEvent;
use Modules\Shipping\Entities\ShippingConfiguration;

class ShippingConfigurationRepository
{
    public function configuration(array $data)
    {
        $seller_id = getParentSellerId();
        $row = ShippingConfiguration::where('seller_id',$seller_id)->first();
        if($row!= null){
            ShippingConfiguration::where('id',$row->id)->update([
                'seller_id'=>$seller_id,
                'order_confirm_and_sync'=>isset($data['order_confirm_and_sync']) ? $data['order_confirm_and_sync'] : null,
                'carrier_show_for_customer'=>isset($data['carrier_show_for_customer']) ? $data['carrier_show_for_customer'] : null,
                'default_carrier'=>isset($data['default_carrier']) ? $data['default_carrier'] : null,
                'order_auto_confirm'=>isset($data['order_auto_confirm']) ? $data['order_auto_confirm'] : null,
                'pickup_location'=>isset($data['pickup_location']) ? $data['pickup_location'] : null,
                'refund_order_sync_carrier'=>isset($data['refund_order_sync_carrier']) ? $data['refund_order_sync_carrier'] : null,
                'seller_use_shiproket'=>isset($data['seller_use_shiproket']) ? $data['seller_use_shiproket'] : null,
                'carrier_order_type'=>isset($data['carrier_order_type']) ? $data['carrier_order_type'] : null,
                'label_code'=>isset($data['label_code']) ? $data['label_code'] : null,
                'amount_multiply_with_qty' => isset($data['amount_multiply_with_qty'])?$data['amount_multiply_with_qty']:0
            ]);

        }else{
             ShippingConfiguration::create([
                 'seller_id'=>$seller_id,
                 'order_confirm_and_sync'=>isset($data['order_confirm_and_sync']) ? $data['order_confirm_and_sync'] : null,
                 'carrier_show_for_customer'=>isset($data['carrier_show_for_customer']) ? $data['carrier_show_for_customer'] : null,
                 'default_carrier'=>isset($data['default_carrier']) ? $data['default_carrier'] : null,
                 'order_auto_confirm'=>isset($data['order_auto_confirm']) ? $data['order_auto_confirm'] : null,
                 'pickup_location'=>isset($data['pickup_location']) ? $data['pickup_location'] : null,
                 'refund_order_sync_carrier'=>isset($data['refund_order_sync_carrier']) ? $data['refund_order_sync_carrier'] : null,
                 'seller_use_shiproket'=>isset($data['seller_use_shiproket']) ? $data['seller_use_shiproket'] : null,
                 'carrier_order_type'=>isset($data['carrier_order_type']) ? $data['carrier_order_type'] : null,
                 'label_code'=>isset($data['label_code']) ? $data['label_code'] : null,
                 'amount_multiply_with_qty' => isset($data['amount_multiply_with_qty'])?$data['amount_multiply_with_qty']:0
             ]);
        }
        return true;

    }

    public function sellerConfig()
    {
        $seller_id = getParentSellerId();
        $setting =  ShippingConfiguration::where('seller_id',$seller_id)->first();
        if($setting){
            return $setting;
        }
        return Event::dispatch(new SellerShippingConfigEvent($seller_id));

    }

}
