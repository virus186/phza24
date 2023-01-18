<?php
namespace App\Repositories;

use App\Models\Cart;
use App\Models\Subscription;
use App\Models\User;
use App\Traits\GoogleAnalytics4;
use App\Traits\PickupLocation;
use Illuminate\Support\Facades\Session;
use Modules\Customer\Entities\CustomerAddress;
use Modules\GiftCard\Entities\GiftCard;
use Modules\INTShipping\Entities\RateZone;
use Modules\INTShipping\Entities\SellerProductShippingProfile;
use Modules\INTShipping\Entities\ShippingProfile;
use Modules\INTShipping\Entities\ShippingZone;
use Modules\PaymentGateway\Entities\PaymentMethod;
use Modules\Seller\Entities\SellerProductSKU;
use Modules\Setup\Entities\Country;
use Modules\Shipping\Entities\PickupLocation as EntitiesPickupLocation;
use Modules\Shipping\Entities\ShippingMethod;

class CheckoutRepository{

    use GoogleAnalytics4,PickupLocation;

    public function __construct(){

    }


    public function getCartItem()
    {
        $carts = [];
        $gift_card_exsist = 0;

        $carts = $this->cartQuery();
        
        $e_productName = 'Product';
        $e_sku = 'sku';
        $e_items = [];
        foreach ($carts as $c){
            if($c->product_type == 'product'){
                if($c->product->product->product->is_physical == 0){
                    $gift_card_exsist += 1;
                }
            }else{
                $gift_card_exsist += 1;
            }

            //ga4
            if(app('business_settings')->where('type', 'google_analytics')->first()->status == 1){
                if($c['product_type'] == 'product'){
                    $e_product = SellerProductSKU::find($c['product_id']);
                    if($e_product){
                        $e_productName = $e_product->product->product_name;
                        $e_sku = $e_product->sku->sku;
                    }
                }else{
                    $e_product = GiftCard::find($c['product_id']);
                    if($e_product){
                        $e_productName = $e_product->name;
                        $e_sku = $e_product->sku;

                    }
                }
                $e_items[]=[
                    "item_id"=> $e_sku,
                    "item_name"=> $e_productName,
                    "currency"=> currencyCode(),
                    "price"=> $c['price']
                ];
            }
        }

        if(app('business_settings')->where('type', 'google_analytics')->first()->status == 1){
            $eData = [
                'name' => 'begin_checkout',
                'params' => [
                    "currency" => currencyCode(),
                    "value"=> 1,
                    "items" => $e_items,
                ],
            ];
            $this->postEvent($eData);
        }
        
        //end ga4



        if(!isModuleActive('MultiVendor')){
            $group['cartData'] = $carts;
        }else{
            $recs = new \Illuminate\Database\Eloquent\Collection($carts);

            $grouped = $recs->groupBy('seller_id');
            $group['cartData'] = $grouped;
        }
        $group['gift_card_exist'] = $gift_card_exsist;
        return $group;
    }


    public function deleteProduct($data){
        $product = Cart::where('user_id',auth()->user()->id)->where('id',$data['id'])->firstOrFail();
        return $product->delete();
    }

    public function addressStore($data){
        $prev_addresses = CustomerAddress::where('customer_id', auth()->id())->get();
        foreach($prev_addresses as $address_old){
            $address_old->update([
                'is_shipping_default' => 0,
                'is_billing_default' => 0
            ]);
        }
        return CustomerAddress::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'country' => $data['country'],
            'postal_code' => $data['postal_code'],
            'is_shipping_default' => 1,
            'is_billing_default' => 1,
            'customer_id' => auth()->user()->id
        ]);
    }
    public function addressUpdate($data){
        $address = CustomerAddress::where('customer_id', auth()->id())->where('id', $data['address_id'])->first();
        $other_addresses = CustomerAddress::where('customer_id', auth()->id())->where('id','!=', $data['address_id'])->get();

        foreach($other_addresses as $address_old){
            $address_old->update([
                'is_shipping_default' => 0,
                'is_billing_default' => 0
            ]);
        }
        $address->update([
            'name' => $data['name'],
            'address' => $data['address'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'postal_code' => $data['postal_code'],
            'is_shipping_default' => 1,
            'is_billing_default' => 1
        ]);
    }

    public function get_active_shipping_methods(){  
        $methods = ShippingMethod::where('is_active', 1)->where('is_approved', 1)->where('id', '>', 1)->whereHas('carrier', function($q){
            $q->where('status', 1);
        })->with(['carrier'])->get();

        if(!isModuleActive('ShipRocket')){
            $methods = $methods->filter(function($item) {
                if($item->carrier->slug != 'Shiprocket'){
                    return $item->id;
                }
            });
        }
        return $methods;
    }

    public function getActivePickup_loactions(){
        return EntitiesPickupLocation::where('created_by', 1)->where('status', 1)->get();
    }

    public function freeShippingForPickup(){
        $free_shipping = ShippingMethod::where('id','>',1)->where('request_by_user', 1)->orderBy('cost')->first();
        return $free_shipping;
    }

    public function guestAddressStore($data)
    {
        $cartData = [];
        $cartData['name'] = $data['name'];
        $cartData['email'] = $data['email'];
        $cartData['phone'] = $data['phone'];
        $cartData['address'] = $data['address'];
        $cartData['city'] = $data['city'];
        $cartData['state'] = $data['state'];
        $cartData['country'] = $data['country'];
        $cartData['postal_code'] = $data['postal_code'];
        Session::put('shipping_address', $cartData);
        return true;
    }

    public function billingAddressStore($data){
        if(auth()->check()){
            $prev_address = CustomerAddress::where('customer_id', auth()->id())->where('is_billing_default', 1)->first();
            if($prev_address){
                $prev_address->update([
                    'is_billing_default' => 0
                ]);
            }

            if($data['address_id'] == 0){
                CustomerAddress::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'country' => $data['country'],
                    'postal_code' => $data['postal_code'],
                    'is_shipping_default' => 0,
                    'is_billing_default' => 1,
                    'customer_id' => auth()->user()->id
                ]);
            }else{
                CustomerAddress::where('customer_id', auth()->id())->where('id',$data['address_id'])->first()->update([
                    'name' => $data['name'],
                    'address' => $data['address'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'country' => $data['country'],
                    'state' => $data['state'],
                    'city' => $data['city'],
                    'postal_code' => $data['postal_code'],
                    'is_shipping_default' => 0,
                    'is_billing_default' => 1
                ]);
            }
            return 1;
        }else{
            $address = [];
            $address['name'] = $data['name'];
            $address['email'] = $data['email'];
            $address['phone'] = $data['phone'];
            $address['address'] = $data['address'];
            $address['city'] = $data['city'];
            $address['state'] = $data['state'];
            $address['country'] = $data['country'];
            $address['postal_code'] = $data['postal_code'];
            Session::put('billing_address', $address);
            return 1;
        }
        return 0;

    }

    public function shippingAddressStore($data){
        if(auth()->check()){
            $other_addresses = CustomerAddress::where('customer_id', auth()->id())->where('id','!=', $data['address_id'])->get();
            foreach($other_addresses as $address_old){
                $address_old->update([
                    'is_shipping_default' => 0,
                    'is_billing_default' => 0
                ]);
            }

            if($data['address_id'] == 0){
                CustomerAddress::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'country' => $data['country'],
                    'postal_code' => $data['postal_code'],
                    'is_shipping_default' => 1,
                    'is_billing_default' => 1,
                    'customer_id' => auth()->user()->id
                ]);
            }else{
                CustomerAddress::where('customer_id', auth()->id())->where('id',$data['address_id'])->first()->update([
                    'name' => $data['name'],
                    'address' => $data['address'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'country' => $data['country'],
                    'state' => $data['state'],
                    'city' => $data['city'],
                    'postal_code' => $data['postal_code'],
                    'is_shipping_default' => 1,
                    'is_billing_default' => 1
                ]);
            }
            return 1;
        }else{
            $address = [];
            $address['name'] = $data['name'];
            $address['email'] = $data['email'];
            $address['phone'] = $data['phone'];
            $address['address'] = $data['address'];
            $address['city'] = $data['city'];
            $address['state'] = $data['state'];
            $address['country'] = $data['country'];
            $address['postal_code'] = $data['postal_code'];
            Session::put('shipping_address', $address);
            return 1;
        }
        return 0;
    }

    public function shippingAddressChange($data){
        if($data['id'] != 0){
            $address = CustomerAddress::where('customer_id',auth()->user()->id)->where('is_shipping_default',1)->first();
            if($address){
                $address->update([
                    'is_shipping_default' => 0
                ]);
            }
            CustomerAddress::findOrFail($data['id'])->update([
                'is_shipping_default' => 1
            ]);
        }else{
            $addresses = CustomerAddress::where('customer_id',auth()->user()->id)->get();
            foreach ($addresses as $address){
                $address->update([
                    'is_shipping_default' => 0,
                    'is_billing_default' => 0
                ]);
            }
        }
        return true;
    }
    public function subscribeFromCheckout($email){
        $old_sub = Subscription::where('email', $email)->first();
        if(!$old_sub){
            Subscription::create([
                'email' =>$email,
                'status' => 1
            ]);
        }
        return true;
    }


    public function getCountries(){
        return Country::where('status', 1)->orderBy('name')->get();
    }

    public function activeShippingAddress(){
        if(auth()->check()){
            $address = auth()->user()->customerShippingAddress;
        }else{
            $address = (object) session()->get('shipping_address');
        }
        return $address;
    }

    public function activeBillingAddress(){
        $billingAddress = null;
        if(auth()->check()){
            $billingAddress = auth()->user()->customerAddresses->where('is_billing_default',1)->where('is_shipping_default',0)->first();
        }else{
            if(session()->has('billing_address')){
                $billingAddress = (object) session()->get('billing_address');
            }
        }
        return $billingAddress;
    }

    public function selectedShippingMethod($id){
        return ShippingMethod::find($id);
    }

    public function totalAmountForPayment($cartData, $shipping=null, $address=null){
        $total = 0;
        $tax = 0;
        $subtotal = 0;
        $actual_price = 0;
        $packagewise_tax = [];
        if(isModuleActive('MultiVendor')){
            $shipping_cost = [];
            $delivery_date = [];
            $shipping_method = [];
        }else{
            $shipping_cost = 0;
            $delivery_date = '';
            $shipping_method = null;
        }
        $additional_shipping = 0;
        $discount = 0;
        $number_of_item = 0;
        $number_of_package = 0;
        $is_digital_product = 0;
        $is_physical_product = 0;
        $gstAmount = 0;
        $e_items = [];

        if(isModuleActive('MultiVendor')){
            $cart_sl = 0;
            foreach($cartData as $seller_id => $packages){

                $seller = User::find($seller_id);
                $number_of_package += 1;
                $package_tax = 0;
                $package_wise_shipping_cost = 0;
                $package_wise_shipping_method = 0;
                $shipping_qty = 1;
                foreach($packages as $cart_key => $cart){
                    $actual_price += ($cart->price * $cart->qty);
                    if($cart->product_type == 'product'){

                        if(file_exists(base_path().'/Modules/GST/') && $cart->product->product->product->is_physical == 1){
                            if($address != null && app('gst_config')['enable_gst'] == "gst"){
                                if($seller->role->type == 'superadmin'){
                                    $state_id = PickupLocation::pickupPointAddress($seller->id)->state_id;
                                }else{
                                    $state_id = $seller->SellerBusinessInformation->business_state;
                                }

                                if($state_id == $address->state){
                                    if($cart->product->product->product->gstGroup){
                                        $sameStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                        $sameStateTaxesGroup = (array) $sameStateTaxesGroup;

                                        foreach($sameStateTaxesGroup as $key => $sameStateTax){
                                            $gstAmount = ($cart->total_price * $sameStateTax) / 100;
                                            $tax += $gstAmount;
                                            $package_tax += $gstAmount;
                                        }
                                    }else{
                                        $sameStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['within_a_single_state'])->get();
                                        foreach($sameStateTaxes as $key => $sameStateTax){
                                            $gstAmount = ($cart->total_price * $sameStateTax->tax_percentage) / 100;
                                            $tax += $gstAmount;
                                            $package_tax += $gstAmount;
                                        }
                                    }
                                }
                                else{
                                    if($cart->product->product->product->gstGroup){
                                        $diffStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->outsite_state_gst);
                                        $diffStateTaxesGroup = (array) $diffStateTaxesGroup;
                                        foreach ($diffStateTaxesGroup as $key => $diffStateTax){
                                            $gstAmount = ($cart->total_price * $diffStateTax) / 100;
                                            $tax += $gstAmount;
                                            $package_tax += $gstAmount;
                                        }
                                    }else{
                                        $diffStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'])->get();
                                        foreach ($diffStateTaxes as $key => $diffStateTax){
                                            $gstAmount = ($cart->total_price * $diffStateTax->tax_percentage) / 100;
                                            $tax += $gstAmount;
                                            $package_tax += $gstAmount;
                                        }
                                    }
                                }
                            }elseif(app('gst_config')['enable_gst'] == "flat_tax"){
                                if($cart->product->product->product->gstGroup){
                                    $flatTaxGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                    $flatTaxGroup = (array) $flatTaxGroup;
                                    foreach($flatTaxGroup as $sameStateTax){
                                        $gstAmount = $cart->total_price * $sameStateTax / 100;
                                        $tax += $gstAmount;
                                    }
                                }else{
                                    $flatTax = \Modules\GST\Entities\GstTax::where('id', app('gst_config')['flat_tax_id'])->first();
                                    $gstAmount = ($cart->total_price * $flatTax->tax_percentage) / 100;
                                    $tax += $gstAmount;
                                    $package_tax += $gstAmount;
                                } 
                            }
                        }else{
                            if($cart->product->product->product->gstGroup){
                                $sameStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                                foreach ($sameStateTaxesGroup as $key => $sameStateTax){
                                    $gstAmount = ($cart->total_price * $sameStateTax) / 100;
                                    $tax += $gstAmount;
                                }
                            }else{
                                $sameStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['within_a_single_state'])->get();
                                foreach ($sameStateTaxes as $key => $sameStateTax){
                                    $gstAmount = ($cart->total_price * $sameStateTax->tax_percentage) / 100;
                                    $tax += $gstAmount;
                                }
                            }
                        }
                        $additional_shipping += $cart->product->sku->additional_shipping;
                        if($cart->product->product->product->is_physical == 0){
                            $is_digital_product  = 1;
                        }else{
                            $is_physical_product = 1;
                            $shipping_qty += 1;
                        }
                        if (isModuleActive('WholeSale')){
                            $w_main_price = 0;
                            $wholeSalePrices = $cart->product->wholeSalePrices;
                            if($wholeSalePrices->count()){
                                foreach ($wholeSalePrices as $w_p){
                                    if ( ($w_p->min_qty<=$cart->qty) && ($w_p->max_qty >=$cart->qty) ){
                                        $w_main_price = $w_p->selling_price;
                                    }
                                    elseif($w_p->max_qty < $cart->qty){
                                        $w_main_price = $w_p->selling_price;
                                    }
                                }
                            }
                            if ($w_main_price!=0){
                                $subtotal += $w_main_price * $cart->qty;
                            }else{
                                $subtotal += ($cart->product->selling_price * $cart->qty);
                            }
                        }else{
                            $subtotal += ($cart->product->selling_price * $cart->qty);
                        }

                        $e_items[]=[
                            "item_id"=> $cart->product->sku->sku,
                            "item_name"=> $cart->product->product->product_name,
                            "currency"=> currencyCode(),
                            "price"=> $cart->price
                        ];
                        if(isModuleActive('INTShipping') && app('theme')->folder_path == 'amazy' && $shipping){
                            $rate_data = $shipping[$cart_sl];
                            $shippingrate = explode(' ' , $rate_data);
                            $rateitem =  RateZone::where('id',$shippingrate[1])->first();
                            
                            if ($rateitem->zone->profile->user_id == $seller_id) {
                                
                                if($cart_key == 0){
                                    $package_wise_shipping_method = $rateitem;
                                }
                                $product_shipping_cost = 0;
                                if ($rateitem->base_on_item == 1){
                                    if ($rateitem->minimum * 1000 <= $cart->product->sku->weight && $rateitem->maximum * 1000 >= $cart->product->sku->weight){
                                        $product_shipping_cost = ($cart->total_price / 100) * $rateitem->rate_cost + $cart->product->sku->additional_shipping;
                                        $package_wise_shipping_cost += $product_shipping_cost;
                                    }
                                }elseif ($rateitem->base_on_item == 2){
                                    if ($rateitem->minimum <= $cart->price && $rateitem->maximum >= $cart->price){
                                        $product_shipping_cost = ($cart->total_price / 100) * $rateitem->rate_cost + $cart->product->sku->additional_shipping;
                                        $package_wise_shipping_cost += $product_shipping_cost;
                                    }
                                }else{
                                    if ($rateitem->minimum <= $cart->price && $rateitem->maximum >= $cart->price){
                                        if(sellerWiseShippingConfig($seller_id)['amount_multiply_with_qty']){
                                            $product_shipping_cost = ($rateitem->rate_cost + $cart->product->sku->additional_shipping) * $cart->qty;
                                        }else{
                                            $product_shipping_cost = $rateitem->rate_cost + $cart->product->sku->additional_shipping;
                                        }
                                        
                                        $package_wise_shipping_cost += $product_shipping_cost;
                                    }
                                } 
                            }
                        }
                        $cart_sl += 1;

                    }else{
                        $subtotal +=  ($cart->giftCard->selling_price * $cart->qty);
                        $is_digital_product  = 1;
                        $e_items[]=[
                            "item_id"=> $cart->giftCard->sku,
                            "item_name"=> $cart->giftCard->name,
                            "currency"=> currencyCode(),
                            "price"=> $cart->price
                        ];
                    }
                    $number_of_item += $cart->qty;

                    $additional_cost = 0;
                    $totalItemPrice = 0;
                    $totalItemWeight = 0;
                    $totalItemHeight = 0;
                    $totalItemLength = 0;
                    $totalItemBreadth = 0;
                    $physical_count = 0;
                    $item_in_cart = 0;
                    if($cart->product_type == 'product' && $cart->product->product->product->is_physical == 1){
                        if(sellerWiseShippingConfig($seller_id)['amount_multiply_with_qty']){
                            $additional_cost += ($cart->product->sku->additional_shipping * $cart->qty);
                        }else{
                            $additional_cost += $cart->product->sku->additional_shipping;
                        }
                        $totalItemPrice += $cart->total_price;
                        $totalItemWeight += !empty($cart->product->sku->weight) ? $cart->qty * $cart->product->sku->weight : 0;
                        $totalItemHeight += $cart->qty * $cart->product->sku->height;
                        $totalItemLength += $cart->qty * $cart->product->sku->length;
                        $totalItemBreadth += $cart->qty * $cart->product->sku->breadth;
                        $physical_count += 1;
                        $item_in_cart += $cart->qty;
                    }

                }
                $packagewise_tax[] = $package_tax;
                if(isModuleActive('INTShipping') && app('theme')->folder_path == 'amazy'){
                    array_push($shipping_cost,$package_wise_shipping_cost);
                }else{
                    
                    $package_wise_shipping = session()->get('package_wise_shipping');
                    if($package_wise_shipping && @$package_wise_shipping[$seller_id]['shipping_id']){
    
                        $shippingMethod = ShippingMethod::with(['carrier'])->find($package_wise_shipping[$seller_id]['shipping_id']);
                        if($shippingMethod){
                            $shipping_cost[] = $package_wise_shipping[$seller_id]['shipping_cost'];
                        }
                    }else{
                        if($is_physical_product){
                            
                            $a_carriers = \Modules\Shipping\Entities\Carrier::where('type','Automatic')->whereHas('carrierConfigFrontend',function ($q) use ($seller_id){
                                $q->where('seller_id',$seller_id)->where('carrier_status',1);
                            });
                            $m_carriers = \Modules\Shipping\Entities\Carrier::where('type','Manual')->where('status', 1)->where('created_by',$seller_id);
                            if(sellerWiseShippingConfig(1)['seller_use_shiproket']){
                                $carriers = $a_carriers->unionAll($m_carriers)->get()->pluck('id')->toArray();
                            }else{
                                $carriers = $m_carriers->get()->pluck('id')->toArray();
                            }
                            $shippingMethod = $this->get_active_shipping_methods()->where('request_by_user',$seller_id)->whereIn('carrier_id',$carriers)->first();
                            
                            $shippingMethods = $this->get_active_shipping_methods()->where('request_by_user',$seller_id)->whereIn('carrier_id',$carriers);
                            foreach($shippingMethods as $shipping_rate){
                                $seller_shipping_cost = 0;
                                if($shipping_rate->cost_based_on == 'Price'){
                                    if($totalItemPrice > 0 && $shipping_rate->cost > 0){
                                        $seller_shipping_cost = ($totalItemPrice / 100) *  $shipping_rate->cost + $additional_cost;
                                    }
                 
                                }elseif ($shipping_rate->cost_based_on == 'Weight'){
                                    if($totalItemWeight > 0 && $shipping_rate->cost > 0){
                                        $seller_shipping_cost = ($totalItemWeight / 100) *  $shipping_rate->cost + $additional_cost;
                                    }
                                }else{
                                    if($shipping_rate->cost > 0){
                                        if(sellerWiseShippingConfig($seller_id)['amount_multiply_with_qty']){
                                            $seller_shipping_cost = ($shipping_rate->cost * $shipping_qty) + $additional_cost;
                                        }else{
                                            $seller_shipping_cost = $shipping_rate->cost + $additional_cost;
                                        }
                                    }else{
                                        $seller_shipping_cost = 0;
                                    }
                                }
                                $total_check = $totalItemPrice + $additional_cost + $seller_shipping_cost;
                                if($total_check >= $shipping_rate->minimum_shopping){
                                    $shippingMethod = $shipping_rate;
                                    break;
                                }
                            }
                            
                        }else{
                            $shippingMethod = ShippingMethod::first();
                            $shipping_cost[] = 0;
                        }
                    }
                }
                // generate delivery date
                if(!isModuleActive('INTShipping') || app('theme')->folder_path == 'default'){
                    $delivery_date[] = $this->generateDeliveryDate($shippingMethod);
                    $shipping_method[] = $shippingMethod->id;
                }elseif (isModuleActive('INTShipping') && app('theme')->folder_path == 'amazy' && $shipping) {
                    $delivery_date[] = $this->generateDeliveryDate($package_wise_shipping_method);
                    $shipping_method[] = $package_wise_shipping_method->id;
                }
            }
        }
        else{
            $sameStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['within_a_single_state'])->get();
            $diffStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'])->get();
            $flatTax = \Modules\GST\Entities\GstTax::where('id', app('gst_config')['flat_tax_id'])->first();
            $cart_sl = 0;
            $package_wise_shipping_method = 0;
            $package_wise_shipping_cost = 0;
            $shipping_qty = 1;
            foreach($cartData as $key => $cart){
                $actual_price += ($cart->price * $cart->qty);
                if($cart->product_type == 'product'){
                    // for whole sale
                    if (isModuleActive('WholeSale')){
                        $w_main_price = 0;
                        $wholeSalePrices = @$cart->product->wholeSalePrices;
                        if($wholeSalePrices->count()){
                            foreach ($wholeSalePrices as $w_p){
                                if ( ($w_p->min_qty<=$cart->qty) && ($w_p->max_qty >=$cart->qty) ){
                                    $w_main_price = $w_p->selling_price;
                                }
                                elseif($w_p->max_qty < $cart->qty){
                                    $w_main_price = $w_p->selling_price;
                                }
                            }
                        }
                        if ($w_main_price!=0){
                            $subtotal += $w_main_price * $cart->qty;
                        }else{
                            $subtotal += ($cart->product->selling_price * $cart->qty);
                        }
                    }else{
                        $subtotal += ($cart->product->selling_price * $cart->qty);
                    }

                    if(file_exists(base_path().'/Modules/GST/') && $cart->product->product->product->is_physical == 1){

                        if($address!=null && app('gst_config')['enable_gst'] == "gst"){
                            if(PickupLocation::pickupPointAddress(1)->state_id == $address->state){
                                if($cart->product->product->product->gstGroup){
                                    $sameStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                    $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                                    foreach($sameStateTaxesGroup as $key => $sameStateTax){
                                        $gstAmount = ($cart->total_price * $sameStateTax) / 100;
                                        $tax += $gstAmount;
                                    }
                                }else{
                                    
                                    foreach($sameStateTaxes as $key => $sameStateTax){
                                        $gstAmount = ($cart->total_price * $sameStateTax->tax_percentage) / 100;
                                        $tax += $gstAmount;
                                    }
                                }
                            }
                            else{
                                if($cart->product->product->product->gstGroup){
                                    $diffStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->outsite_state_gst);
                                    $diffStateTaxesGroup = (array) $diffStateTaxesGroup;
                                    foreach ($diffStateTaxesGroup as $key => $diffStateTax){
                                        $gstAmount = ($cart->total_price * $diffStateTax) / 100;
                                        $tax += $gstAmount;
                                    }
                                }else{
                                    foreach ($diffStateTaxes as $key => $diffStateTax){
                                        $gstAmount = ($cart->total_price * $diffStateTax->tax_percentage) / 100;
                                        $tax += $gstAmount;
                                    }
                                }
                            }
                        }
                        elseif(app('gst_config')['enable_gst'] == "flat_tax"){
                            if($cart->product->product->product->gstGroup){
                                $flatTaxGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                $flatTaxGroup = (array) $flatTaxGroup;
                                foreach($flatTaxGroup as $sameStateTax){
                                    $gstAmount = $cart->total_price * $sameStateTax / 100;
                                    $tax += $gstAmount;
                                }
                            }else{
                                
                                $gstAmount = $cart->total_price * $flatTax->tax_percentage / 100;
                                $tax += $gstAmount;
                            }
                        }
                    }else{
                        if($cart->product->product->product->gstGroup){
                            $sameStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                            $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                            foreach ($sameStateTaxesGroup as $key => $sameStateTax){
                                $gstAmount = ($cart->total_price * $sameStateTax) / 100;
                                $tax += $gstAmount;
                            }
                        }else{
                            foreach ($sameStateTaxes as $key => $sameStateTax){
                                $gstAmount = ($cart->total_price * $sameStateTax->tax_percentage) / 100;
                                $tax += $gstAmount;
                            }
                        }
                    }
                    if($cart->product->product->product->is_physical == 0){
                        $is_digital_product  = 1;
                    }else{
                        $is_physical_product = 1;
                        $shipping_qty += 1;
                        if(sellerWiseShippingConfig(1)['amount_multiply_with_qty']){
                            $additional_shipping += $cart->product->sku->additional_shipping * $cart->qty;
                        }else{
                            $additional_shipping += $cart->product->sku->additional_shipping;
                        }
                    }
                    $e_items[]=[
                        "item_id"=> $cart->product->sku->sku,
                        "item_name"=> $cart->product->product->product_name,
                        "currency"=> currencyCode(),
                        "price"=> $cart->price
                    ];

                    if(isModuleActive('INTShipping') && app('theme')->folder_path == 'amazy' && $shipping){
                        $rate_data = $shipping[$cart_sl];
                        $shippingrate = explode(' ' , $rate_data);
                        $rateitem =  RateZone::where('id',$shippingrate[1])->first();
                            
                        if($rateitem){
                            if($key == 0){
                                $package_wise_shipping_method = $rateitem;
                            }
                            $product_shipping_cost = 0;
                            if ($rateitem->base_on_item == 1){
                                if ($rateitem->minimum * 1000 <= $cart->product->sku->weight && $rateitem->maximum * 1000 >= $cart->product->sku->weight){
                                    $product_shipping_cost = ($cart->total_price / 100) * $rateitem->rate_cost + $cart->product->sku->additional_shipping;
                                    $package_wise_shipping_cost += $product_shipping_cost;
                                }
                            }elseif ($rateitem->base_on_item == 2){
                                if ($rateitem->minimum <= $cart->price && $rateitem->maximum >= $cart->price){
                                    $product_shipping_cost = ($cart->total_price / 100) * $rateitem->rate_cost + $cart->product->sku->additional_shipping;
                                    $package_wise_shipping_cost += $product_shipping_cost;
                                }
                            }else{
                                if ($rateitem->minimum <= $cart->price && $rateitem->maximum >= $cart->price){
                                    if(sellerWiseShippingConfig(1)['amount_multiply_with_qty']){
                                        $product_shipping_cost = ($rateitem->rate_cost + $cart->product->sku->additional_shipping) * $cart->qty;
                                    }else{
                                        $product_shipping_cost = $rateitem->rate_cost + $cart->product->sku->additional_shipping;
                                    }
                                    
                                    $package_wise_shipping_cost += $product_shipping_cost;
                                }
                            }
                        } 
                    }
                    $cart_sl += 1;

                }else{
                    $subtotal +=  ($cart->giftCard->selling_price * $cart->qty);
                    $is_digital_product  = 1;
                    $e_items[]=[
                        "item_id"=> $cart->giftCard->sku,
                        "item_name"=> $cart->giftCard->name,
                        "currency"=> currencyCode(),
                        "price"=> $cart->price
                    ];
                }
                $number_of_item += $cart->qty;
                $packagewise_tax[] = $gstAmount;

                $totalItemWeight = 0;
                $physical_count = 0;
                if($cart->product_type == 'product' && @$cart->product->product->product->is_physical == 1){
                    $totalItemWeight += !empty($cart->product->sku->weight) ? $cart->qty * $cart->product->sku->weight : 0;
                    $physical_count += 1;
                }
            }
            if($shipping!=null){
                if(session()->has('delivery_info') && session()->get('delivery_info')['delivery_type'] == 'pickup_location'){
                    $shipping_cost = 0;
                }else{
                    if(isModuleActive('INTShipping') && app('theme')->folder_path == 'amazy'){
                        $shipping_cost = $package_wise_shipping_cost;
                    }else{
                        if($shipping->cost_based_on == 'Price'){
                            if($actual_price > 0 && $shipping->cost > 0){
                                $shipping_cost = ($actual_price / 100) *  $shipping->cost + $additional_shipping;
                            }else{
                                $shipping_cost = 0;
                            }
         
                        }elseif ($shipping->cost_based_on == 'Weight'){
                            if($totalItemWeight > 0 && $shipping->cost > 0){
                                $shipping_cost = ($totalItemWeight / 100) *  $shipping->cost + $additional_shipping;
                            }else{
                                $shipping_cost = 0;
                            }
                        }else{
                            if($shipping->cost > 0){
                                if(sellerWiseShippingConfig(1)['amount_multiply_with_qty']){
                                    $shipping_cost = ($shipping->cost * $shipping_qty) + $additional_shipping;
                                }else{
                                    $shipping_cost = $shipping->cost + $additional_shipping;
                                }
                                
                            }else{
                                $shipping_cost = 0;
                            }
                        }
                    }
                }
                //delivery_date generate
                if(isModuleActive('INTShipping') && app('theme')->folder_path == 'amazy'){
                    $delivery_date = $this->generateDeliveryDate($package_wise_shipping_method);
                    $shipping_method = $package_wise_shipping_method->id;
                }else{
                    $delivery_date = $this->generateDeliveryDate($shipping);
                    $shipping_method = $shipping->id;
                }
            }
            if($is_digital_product == 1 && $is_physical_product == 1){
                $number_of_package = 2;
            }else{
                $number_of_package = 1;
            }
        }

        //ga4
        if(app('business_settings')->where('type', 'google_analytics')->first()->status == 1){
            $eData = [
                'name' => 'add_shipping_info',
                'params' => [
                    "currency" => currencyCode(),
                    "value"=> 1,
                    "items" => $e_items,
                ],
            ];
            $this->postEvent($eData);
        }
        //end ga4
        if(isModuleActive('MultiVendor')){
            $t_shipping = collect($shipping_cost)->sum();
            $total = $actual_price + $tax + $t_shipping;
        }else{
            $total = $actual_price + $tax + $shipping_cost;
        }
        $discount = $subtotal - $actual_price;
        $result = [
            'grand_total' => $total,
            'subtotal' => $subtotal,
            'actual_total' => $actual_price,
            'discount' => $discount,
            'number_of_item' => $number_of_item,
            'number_of_package' => $number_of_package,
            'shipping_cost' => $shipping_cost,
            'tax_total' => $tax,
            'delivery_date' => $delivery_date,
            'shipping_method' => $shipping_method,
            'packagewise_tax' => $packagewise_tax
        ];

        return $result;
    }

    public function getActivePaymentGetways(){
        if(isModuleActive('MultiVendor')){
            return PaymentMethod::where('active_status', 1)->whereHas('sellerPaymentMethod', function($query){
                return $query->where('status', 1);
            });
        }
        return PaymentMethod::where('active_status', 1);
    }

    private function generateDeliveryDate($shipping){
        $shipment_time = $shipping->shipment_time;
        $shipment_time = explode(" ", $shipment_time);
        $dayOrOur = $shipment_time[1];

        $shipment_time = explode("-", $shipment_time[0]);
        $start_ = $shipment_time[0];
        $end_ = $shipment_time[1];
        $date = date('d-m-Y');
        $start_date = date('d M', strtotime($date. '+ '.$start_.' '.$dayOrOur));
        $end_date = date('d M', strtotime($date. '+ '.$end_.' '.$dayOrOur));

        if($dayOrOur == 'days' || $dayOrOur == 'Days' ||$dayOrOur == 'Day'){
            $delivery_date = 'Est arrival date: '. $start_date.' '.'-'.' '.$end_date;
        }else{
            $delivery_date = 'Est arrival time: '. $shipping->shipment_time;
        }
        return $delivery_date;
    }

    private function cartQuery(){
        if(auth()->check()){
            if(session()->has('buy_it_now') && session()->get('buy_it_now') == 'yes'){
                $carts = Cart::with(['product.product.product.skus'])->where('is_buy_now', 1)->where('user_id',auth()->user()->id)->where('is_select',1)->where('product_type', 'product')->whereHas('product', function($query){
                    return $query->where('status', 1)->whereHas('product', function($q){
                        return $q->where('status', 1)->activeSeller();
                    });
                })->orWhere('product_type', 'gift_card')->where('is_buy_now', 1)->where('user_id',auth()->user()->id)->where('is_select',1)->whereHas('giftCard', function($query){
                    return $query->where('status', 1);
                })->latest()->take(1)->get();
            }else{
                if(session()->has('seller_for_checkout')){
                    $carts = Cart::with(['product.product.product.skus'])->where('user_id',auth()->user()->id)->where('seller_id', session()->get('seller_for_checkout'))->where('is_select',1)->where('product_type', 'product')->whereHas('product', function($query){
                        return $query->where('status', 1)->whereHas('product', function($q){
                            return $q->where('status', 1)->activeSeller();
                        });
                    })->orWhere('product_type', 'gift_card')->where('user_id',auth()->user()->id)->where('seller_id', session()->get('seller_for_checkout'))->where('is_select',1)->whereHas('giftCard', function($query){
                        return $query->where('status', 1);
                    })->get();
                }else{
                    $carts = Cart::with(['product.product.product.skus'])->where('user_id',auth()->user()->id)->where('is_select',1)->where('product_type', 'product')->whereHas('product', function($query){
                        return $query->where('status', 1)->whereHas('product', function($q){
                            return $q->where('status', 1)->activeSeller();
                        });
                    })->orWhere('product_type', 'gift_card')->where('user_id',auth()->user()->id)->where('is_select',1)->whereHas('giftCard', function($query){
                        return $query->where('status', 1);
                    })->get();
                }
                
            }
        }else{
            if(session()->has('buy_it_now') && session()->get('buy_it_now') == 'yes'){
                $carts = Cart::where('session_id',session()->getId())->where('is_select',1)->where('is_buy_now', 1)->where('product_type', 'product')->whereHas('product', function($query){
                    return $query->where('status', 1)->whereHas('product', function($q){
                        return $q->where('status', 1)->activeSeller();
                    });
                })->orWhere('product_type', 'gift_card')->where('session_id',session()->getId())->where('is_buy_now', 1)->where('is_select',1)->whereHas('giftCard', function($query){
                    return $query->where('status', 1);
                })->latest()->take(1)->get();
            }else{
                if(session()->has('seller_for_checkout')){
                    $carts = Cart::where('session_id',session()->getId())->where('is_select',1)->where('seller_id', session()->get('seller_for_checkout'))->where('product_type', 'product')->whereHas('product', function($query){
                        return $query->where('status', 1)->whereHas('product', function($q){
                            return $q->where('status', 1)->activeSeller();
                        });
                    })->orWhere('product_type', 'gift_card')->where('session_id',session()->getId())->where('is_select',1)->where('seller_id', session()->get('seller_for_checkout'))->whereHas('giftCard', function($query){
                        return $query->where('status', 1);
                    })->get();
                }else{
                    $carts = Cart::where('session_id',session()->getId())->where('is_select',1)->where('product_type', 'product')->whereHas('product', function($query){
                        return $query->where('status', 1)->whereHas('product', function($q){
                            return $q->where('status', 1)->activeSeller();
                        });
                    })->orWhere('product_type', 'gift_card')->where('session_id',session()->getId())->where('is_select',1)->whereHas('giftCard', function($query){
                        return $query->where('status', 1);
                    })->get();
                }
            }
        }
        return $carts;
    }

    public function checkCartPriceUpdate(){
        $carts = $this->cartQuery()->where('is_updated',1);
        $count = $carts->count();
        foreach($carts as $cart){
            $cart->update([
                'is_updated' => 0
            ]);
        }
        return $count;
    }

    public function checkCartPriceUpdateAPI($user){
        $carts = Cart::with(['product.product.product.skus'])->where('user_id',$user->id)->where('is_select',1)->where('product_type', 'product')->whereHas('product', function($query){
            return $query->where('status', 1)->whereHas('product', function($q){
                return $q->where('status', 1)->activeSeller();
            });
        })->where('is_updated',1)->orWhere('product_type', 'gift_card')->where('user_id',$user->id)->where('is_select',1)->whereHas('giftCard', function($query){
            return $query->where('status', 1);
        })->where('is_updated',1)->get();
        $count = $carts->count();
        foreach($carts as $cart){
            $cart->update([
                'is_updated' => 0
            ]);
        }
        return $count;
    }

    public function getSellerById($id){
        return User::where('id', $id)->where('is_active', 1)->first();
    }
}
