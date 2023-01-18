<?php

namespace Modules\Shipping\Repositories;

use App\Models\GuestOrderDetail;
use App\Models\OrderAddressDetail;
use App\Models\OrderPackageDetail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Entities\CustomerAddress;
use Modules\OrderManage\Repositories\OrderManageRepository;
use Modules\Shipping\Http\Controllers\OrderSyncWithCarrierController;
use Modules\ShipRocket\Repositories\ShipRocketRepository;

class OrderRepository
{
    public function pendingOrder($filterData)
    {


        $seller_id = getParentSellerId();

        $query = OrderPackageDetail::query()->whereHas('order', function ($q) {
            $q->where('is_cancelled', 0)->where('delivery_type', 'home_delivery');
        })->where('is_cancelled', 0)->where('delivery_status','<=', 2)->where('shipping_method','>',1)->with('order', 'seller', 'order.customer','shipping')->where('seller_id', $seller_id);

        if(!empty($filterData['carrier'])){
            $query = $query->where('carrier_id',$filterData['carrier']);
        }
        if (!empty($filterData['shipping_method'])){
           $query = $query->where('shipping_method',$filterData['shipping_method']);
        }
        if(!empty($filterData['package_code'])){
            $query = $query->where('package_code',$filterData['package_code']);
        }
        if(!empty($filterData['date_range_filter'])){
            $query = $query->whereBetween('created_at',filterDateFormatingForSearchQuery($filterData['date_range_filter']));
        }
        return $query->latest()->get();

    }


    public function order($id)
    {
        return OrderPackageDetail::with('order', 'seller', 'order.customer','shipping')->findOrFail($id);
    }


    public function shippingMethodChange(array $data)
    {
        if(isset($data['multiple_order'])){
            $order_ids = json_decode($data['order_ids']);
            foreach ($order_ids as $id){
                OrderPackageDetail::where('id',$id)->update(['shipping_method'=>$data['shipping_method']]);
                //shipping carrier config
                $order = OrderPackageDetail::find($id);
                $orderManageRepo = new OrderManageRepository();
                $orderManageRepo->orderConfirm($order->order_id);
                //end shipping carrier
            }
            return true;
        }else{



            $response = OrderPackageDetail::where('id',$data['order_id'])->update([
                'shipped_by'=>isset($data['shipping_method'])?$data['shipping_method']:null,
                'carrier_order_id'=>isset($data['tracking_id']) ? $data['tracking_id'] :null,
                'carrier_id'=>$data['carrier'],
                'shipping_method'=>$data['c_shipping_method'],
                'pickup_point_id'=>pickupLocationData('id'),
            ]);
            //shipping carrier config
            $order = OrderPackageDetail::find($data['order_id']);

            if($order->seller_id ==1){
                $orderManageRepo = new OrderManageRepository();
                $orderManageRepo->orderConfirm($order->order_id);
            }
            $syncController = new OrderSyncWithCarrierController();
            $syncController->OrderSyncWithCarrier($order);
            //end shipping carrier

            return true;
        }

    }


    public function findOrderByTrackingId($trackingId)
    {
        return OrderPackageDetail::with(['shipping'])->where('carrier_order_id',$trackingId)->first();
    }

    public function updateCarrierOrder($id)
    {
        $order = $this->order($id);
        if($order->carrier->slug == 'Shiprocket' && $order->carrier->status ==1){
            $shipRocketRepo = new ShipRocketRepository();
            $res = $shipRocketRepo->tracking($order->carrier_order_id);
            return $res['status'];
        }else{
            return 'failed';
        }

    }

    public function updatePackaging(array $data)
    {
        return OrderPackageDetail::where('id',$data['id'])->update([
            'weight'=>$data['weight'],
            'length'=>$data['length'],
            'breadth'=>$data['breadth'],
            'height'=>$data['height'],
        ]);
    }

    public function updateCustomerAddress(array $data)
    {
        if(isset($data['customer_address_id'])){
            OrderAddressDetail::where('id', $data['customer_address_id'])->update([
                'shipping_name'=>$data['shipping_name'],
                'shipping_email'=>$data['shipping_email'],
                'shipping_phone'=>$data['shipping_phone'],
                'shipping_address'=>$data['shipping_address'],
                'shipping_postcode'=>$data['shipping_postcode'],
                'shipping_country_id'=>$data['shipping_country'],
                'shipping_state_id'=>$data['shipping_state'],
                'shipping_city_id'=>$data['shipping_city'],
                'billing_name'=>$data['billing_name'],
                'billing_email'=>$data['billing_email'],
                'billing_phone'=>$data['billing_phone'],
                'billing_address'=>$data['billing_address'],
                'billing_postcode'=>$data['billing_postcode'],
                'billing_country_id'=>$data['billing_country'],
                'billing_state_id'=>$data['billing_state'],
                'billing_city_id'=>$data['billing_city']
            ]);

        }

        if(isset($data['guest_address_id'])){
            GuestOrderDetail::where('id',$data['guest_address_id'])->update([
                'shipping_name'=>$data['shipping_name'],
                'shipping_email'=>$data['shipping_email'],
                'shipping_phone'=>$data['shipping_phone'],
                'shipping_address'=>$data['shipping_address'],
                'shipping_post_code'=>$data['shipping_postcode'],
                'shipping_country_id'=>$data['shipping_country'],
                'shipping_state_id'=>$data['shipping_state'],
                'shipping_city_id'=>$data['shipping_city'],
                'billing_name'=>$data['billing_name'],
                'billing_email'=>$data['billing_email'],
                'billing_phone'=>$data['billing_phone'],
                'billing_address'=>$data['billing_address'],
                'billing_post_code'=>$data['billing_postcode'],
                'billing_country_id'=>$data['billing_country'],
                'billing_state_id'=>$data['billing_state'],
                'billing_city_id'=>$data['billing_city'],
            ]);
        }
        return true;

    }
}
