<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponApplyRequest;
use App\Models\Cart;
use App\Services\CheckoutService;
use App\Traits\GoogleAnalytics4;
use Brian2694\Toastr\Facades\Toastr;
use Modules\GiftCard\Entities\GiftCard;
use \Modules\PaymentGateway\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Modules\Marketing\Entities\Coupon;
use Modules\Marketing\Entities\CouponProduct;
use Modules\Marketing\Entities\CouponUse;
use Modules\Seller\Entities\SellerProductSKU;
use Modules\Setup\Repositories\CityRepository;
use Modules\Setup\Repositories\StateRepository;
use Modules\Shipping\Entities\ShippingMethod;
use Modules\UserActivityLog\Traits\LogActivity;
use Modules\Setup\Entities\Country;
use Modules\Setup\Entities\State;
use Modules\Setup\Entities\City;

class CheckoutController extends Controller
{
    use GoogleAnalytics4;

    protected $checkoutService;
    protected $paymentGatewayService;
    public function __construct(CheckoutService $checkoutService,PaymentGatewayService $paymentGatewayService)
    {
        $this->checkoutService = $checkoutService;
        $this->paymentGatewayService = $paymentGatewayService;
        // $this->middleware('maintenance_mode');

    }

    public function index(Request $request)
    {   
        if(isset($request->checkout_type) && base64_decode($request->checkout_type) == 'buy_it_now'){
            session()->put('buy_it_now', 'yes');
        }else{
            if(isset($request->step) && session()->get('buy_it_now')){
                session()->put('buy_it_now','yes');
            }else{
                session()->forget('buy_it_now');
            }
        }
        if(isModuleActive('MultiVendor') && app('general_setting')->seller_wise_payment){
            if(!isset($request->step)){
                if(!isset($request->owner)){
                    Toastr::error('Invalid Seller', 'Error');
                    return redirect(url('/cart'));
                }
                $seller = $this->checkoutService->getSellerById(decrypt($request->owner));
                if($seller){
                    session()->put('seller_for_checkout',$seller->id);
                }else{
                    session()->forget('seller_for_checkout');
                    Toastr::error('Invalid Checkout. Try again.', 'Error');
                    return redirect(url('/cart'));
                }
            }else{
                if(isset($request->step) && session()->get('seller_for_checkout')){

                }else{
                    session()->forget('seller_for_checkout');
                    Toastr::error('Invalid Checkout. Try again.', 'Error');
                    return redirect(url('/cart'));
                }
            }
            
        }else{
            session()->forget('seller_for_checkout');
        }
        $step = $request->get('step');

        $cartDataGroup = $this->checkoutService->getCartItem();

        $cartData = $cartDataGroup['cartData'];

        if(!$cartData->count()){
            Toastr::info(__('shipping.cart_is_empty_please_add_product_first'), __('common.info'));
            return redirect(url('/cart'));
        }

        $giftCardExist = $cartDataGroup['gift_card_exist'];
        $customer = auth()->user();
        $shipping_address = null;
        if(auth()->check()){
            if(count(auth()->user()->customerAddresses) > 0){
                $shipping_address = auth()->user()->customerAddresses->where('is_shipping_default',1)->first();
                if($shipping_address){
                    $states = (new StateRepository())->getByCountryId($shipping_address->country)->where('status', 1);
                    $cities = (new CityRepository())->getByStateId($shipping_address->state)->where('status', 1);
                }else{
                    $states = (new StateRepository())->getByCountryId(app('general_setting')->default_country)->where('status', 1);
                    $cities = (new CityRepository())->getByStateId(app('general_setting')->default_state)->where('status', 1);
                }
            }else{
                $states = (new StateRepository())->getByCountryId(app('general_setting')->default_country)->where('status', 1);
                $cities = (new CityRepository())->getByStateId(app('general_setting')->default_state)->where('status', 1);
            }

        }else{
            if(session()->has('shipping_address')){
                $shipping_address = (object) session()->get('shipping_address');
                $states = (new StateRepository())->getByCountryId($shipping_address->country)->where('status', 1);
                $cities = (new CityRepository())->getByStateId($shipping_address->state)->where('status', 1);
            }else{
                $states = (new StateRepository())->getByCountryId(app('general_setting')->default_country)->where('status', 1);
                $cities = (new CityRepository())->getByStateId(app('general_setting')->default_state)->where('status', 1);
            }
        }
        $countries = $this->checkoutService->getCountries();
        $gateway_activations = $this->checkoutService->getActivePaymentGetways();
        $shipping_methods = $this->checkoutService->get_active_shipping_methods();

        if(count($cartData) < 1){
            Toastr::warning(__('defaultTheme.please_product_select_from_cart_first'), __('common.warning'));
            return back();
        }
        if($step == 'select_shipping'){
            if (env('NOCAPTCHA_FOR_CHECKOUT') == "true" && app('theme')->folder_path == 'amazy') {
                $g_recaptcha = 'required';
            }else{
                $g_recaptcha = 'nullable';
            }
            $request->validate([
                'name' => 'required',
                'address' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'country' => 'required',
                'g-recaptcha-response' => $g_recaptcha,
            ],[
                'g-recaptcha-response.required' => 'The google recaptcha field is required.',
            ]);
            if($request->get('note') != null){
                session()->put('order_note',$request->get('note'));
            }
            if($request->has('news_letter')){
                $email = '';
                if(auth()->check() && auth()->user()->email != null){
                    $email = auth()->user()->email;
                }else{
                    $email = $request->get('email');
                }
                $this->checkoutService->subscribeFromCheckout($email);
            }
            if(auth()->check()){
                $request->merge([
                    'is_shipping_default' => 1,
                    'is_billing_default' => 1
                ]);
                if($request->get('address_id') != 0){
                    $this->checkoutService->addressUpdate($request->only('address_id','name','address','email','phone','country','state','city','postal_code'));
                }else{
                    $this->checkoutService->addressStore($request->only('name','address','email','phone','country','state','city','postal_code'));
                }

            }else{
                $this->checkoutService->guestAddressStore($request->only('name','address','email','phone','country','state','city','postal_code'));
            }
            $address = $this->checkoutService->activeShippingAddress();

            $data = [
                'cartData' => $cartData,
                'gateway_activations' => $gateway_activations,
                'shipping_address' => $shipping_address,
                'shipping_methods' => $shipping_methods,
                'address' => $address
            ];
            if(isModuleActive('MultiVendor')){
                return redirect(url('/cart'));
            }
            return view(theme('pages.shipping_step'),$data);
        }
        elseif($step == 'select_payment'){
            if(isModuleActive('MultiVendor')){
                if (env('NOCAPTCHA_FOR_CHECKOUT') == "true" && app('theme')->folder_path == 'amazy') {
                    $g_recaptcha = 'required';
                }else{
                    $g_recaptcha = 'nullable';
                }
                $request->validate([
                    'name' => 'required',
                    'address' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'country' => 'required',
                    'g-recaptcha-response' => $g_recaptcha,
                ],[
                    'g-recaptcha-response.required' => 'The google recaptcha field is required.',
                ]);
                if(!isModuleActive('INTShipping')){
                    if(auth()->check()){
                        $request->merge([
                            'is_shipping_default' => 1,
                            'is_billing_default' => 1
                        ]);
                        if($request->get('address_id') != 0){
                            $this->checkoutService->addressUpdate($request->only('address_id','name','address','email','phone','country','state','city','postal_code'));
                        }else{
                            $this->checkoutService->addressStore($request->only('name','address','email','phone','country','state','city','postal_code'));
                        }
    
                    }else{
                        $this->checkoutService->guestAddressStore($request->only('name','address','email','phone','country','state','city','postal_code'));
                    }
                }
            }else{
                $request->validate([
                    'shipping_method' => 'required',
                    'delivery_type' => 'required',
                    'pickup_location' => 'required_if:delivery_type,==,pickup_location'
                ]);
                session()->forget('delivery_info');
                if($request->delivery_type == 'home_delivery'){
                    $delivery_info = [
                        'delivery_type' => 'home_delivery'
                    ];
                    session()->put('delivery_info', $delivery_info);
                }elseif($request->delivery_type == 'pickup_location'){
                    $delivery_info = [
                        'delivery_type' => 'pickup_location',
                        'pickup_location' => $request->pickup_location
                    ];
                    session()->put('delivery_info', $delivery_info);
                    if(auth()->check()){
                        $request->merge([
                            'is_shipping_default' => 1,
                            'is_billing_default' => 1
                        ]);
                        if($request->get('address_id') != 0){
                            $this->checkoutService->addressUpdate($request->only('address_id','name','address','email','phone','country','state','city','postal_code'));
                        }else{
                            $this->checkoutService->addressStore($request->only('name','address','email','phone','country','state','city','postal_code'));
                        }
    
                    }else{
                        $this->checkoutService->guestAddressStore($request->only('name','address','email','phone','country','state','city','postal_code'));
                    }
                }

            }
            if($request->get('note') != null){
                session()->put('order_note',$request->get('note'));
            }
            if($request->has('news_letter')){
                $email = '';
                if(auth()->check() && auth()->user()->email != null){
                    $email = auth()->user()->email;
                }else{
                    $email = $request->get('email');
                }
                $this->checkoutService->subscribeFromCheckout($email);
            }
            if(session()->has('infoCompleteOrder')){
                session()->forget('infoCompleteOrder');
            }
            $address = $this->checkoutService->activeShippingAddress();
            $coupon = [];
            if(isModuleActive('MultiVendor')){
                if(isModuleActive('INTShipping') && app('theme')->folder_path == 'amazy'){
                    $shipping_method_rate = $request->get('intshipping_cartItem');
                    $selected_shipping_method = $this->checkoutService->totalAmountForPayment($cartData,$shipping_method_rate,$address)['shipping_method'];
                    $total_amount = $this->checkoutService->totalAmountForPayment($cartData,$shipping_method_rate,$address)['grand_total'];
                    $subtotal_without_discount = $this->checkoutService->totalAmountForPayment($cartData,$shipping_method_rate,$address)['subtotal'];
                    $discount = $this->checkoutService->totalAmountForPayment($cartData,$shipping_method_rate,$address)['discount'];
                    $number_of_package = $this->checkoutService->totalAmountForPayment($cartData,$shipping_method_rate,$address)['number_of_package'];
                    $number_of_item = $this->checkoutService->totalAmountForPayment($cartData,$shipping_method_rate,$address)['number_of_item'];
                    $shipping_cost = $this->checkoutService->totalAmountForPayment($cartData,$shipping_method_rate,$address)['shipping_cost'];
                    $tax_total = $this->checkoutService->totalAmountForPayment($cartData,$shipping_method_rate,$address)['tax_total'];
                    $delivery_date = $this->checkoutService->totalAmountForPayment($cartData,$shipping_method_rate,$address)['delivery_date'];
                    $packagewise_tax = $this->checkoutService->totalAmountForPayment($cartData,$shipping_method_rate,$address)['packagewise_tax'];
                    $actual_total = $this->checkoutService->totalAmountForPayment($cartData,$shipping_method_rate,$address)['actual_total'];
                }else{
                    $selected_shipping_method = $this->checkoutService->totalAmountForPayment($cartData,null,$address)['shipping_method'];
                    $total_amount = $this->checkoutService->totalAmountForPayment($cartData,null,$address)['grand_total'];
                    $subtotal_without_discount = $this->checkoutService->totalAmountForPayment($cartData,null,$address)['subtotal'];
                    $discount = $this->checkoutService->totalAmountForPayment($cartData,null,$address)['discount'];
                    $number_of_package = $this->checkoutService->totalAmountForPayment($cartData,null,$address)['number_of_package'];
                    $number_of_item = $this->checkoutService->totalAmountForPayment($cartData,null,$address)['number_of_item'];
                    $shipping_cost = $this->checkoutService->totalAmountForPayment($cartData,null,$address)['shipping_cost'];
                    $tax_total = $this->checkoutService->totalAmountForPayment($cartData,null,$address)['tax_total'];
                    $delivery_date = $this->checkoutService->totalAmountForPayment($cartData,null,$address)['delivery_date'];
                    $packagewise_tax = $this->checkoutService->totalAmountForPayment($cartData,null,$address)['packagewise_tax'];
                    $actual_total = $this->checkoutService->totalAmountForPayment($cartData,null,$address)['actual_total'];
                }
                if(Session::has('coupon_type')&&Session::has('coupon_discount')){
                    $coupon = $this->couponCount($subtotal_without_discount-$discount, collect($shipping_cost)->sum());
                }
            }else{
                if(isModuleActive('INTShipping') && app('theme')->folder_path == 'amazy'){
                    $selected_shipping_method = $request->get('intshipping_cartItem');
                }else{
                    $selected_shipping_method = $this->checkoutService->selectedShippingMethod(decrypt($request->get('shipping_method')));
                }
                $total_amount = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address)['grand_total'];
                $subtotal_without_discount = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address)['subtotal'];
                $discount = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address)['discount'];
                $number_of_package = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address)['number_of_package'];
                $number_of_item = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address)['number_of_item'];
                $shipping_cost = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address)['shipping_cost'];
                $tax_total = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address)['tax_total'];
                $delivery_date = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address)['delivery_date'];
                $packagewise_tax = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address)['packagewise_tax'];
                $actual_total = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address)['actual_total'];
                if(isModuleActive('INTShipping') && app('theme')->folder_path == 'amazy'){
                    $selected_shipping_method = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address)['shipping_method'];
                }
                if(Session::has('coupon_type')&&Session::has('coupon_discount')){
                    $coupon = $this->couponCount($subtotal_without_discount-$discount,$shipping_cost);
                }
            }
            if(!auth()->check() || auth()->check() && auth()->user()->CustomerCurrentWalletAmounts < $total_amount){
                $gateway_activations = $gateway_activations->whereNotIn('id',['2']);
            }
            if($giftCardExist > 0){
                $gateway_activations = $gateway_activations->whereNotIn('id',['1']);
            }


            if(!isModuleActive('Bkash')){
                $gateway_activations = $gateway_activations->whereNotIn('id',['15']);
            }

            if(!isModuleActive('SslCommerz')){
                $gateway_activations = $gateway_activations->whereNotIn('id',['16']);
            }
            if(!isModuleActive('MercadoPago')){
                $gateway_activations = $gateway_activations->whereNotIn('id',['17']);
            }

            $gateway_activations = $gateway_activations->get();
            $billing_address = $this->checkoutService->activeBillingAddress();
            $infoCompleteOrder = [
                'cartData' => $cartData,
                'total_amount' => $total_amount,
                'subtotal_without_discount' => $subtotal_without_discount,
                'discount' => $discount,
                'number_of_package' => $number_of_package,
                'number_of_item' => $number_of_item,
                'shipping_cost' => $shipping_cost,
                'selected_shipping_method' => $selected_shipping_method,
                'address' => $address,
                'gateway_activations' => $gateway_activations,
                'tax_total' => $tax_total,
                'delivery_date' => $delivery_date,
                'packagewise_tax' => $packagewise_tax,
                'actual_total' => $actual_total
            ];
            $infoCompleteOrder = array_merge($infoCompleteOrder,$coupon);
            $infoCompleteOrder['countries'] = $countries;

            if(auth()->check() && count(auth()->user()->customerAddresses) > 0){
                $billing_address_check = auth()->user()->customerAddresses->where('is_billing_default',1)->where('is_shipping_default',0)->first();
                if($billing_address_check){
                    $billing_states = (new StateRepository())->getByCountryId($billing_address_check->country)->where('status', 1);
                    $billing_cities = (new CityRepository())->getByStateId($billing_address_check->state)->where('status', 1);
                }else{
                    $billing_states = (new StateRepository())->getByCountryId(app('general_setting')->default_country)->where('status', 1);
                    $billing_cities = (new CityRepository())->getByStateId(app('general_setting')->default_state)->where('status', 1);
                }
    
            }else{
                if(session()->has('billing_address')){
                    $billing_address_check = (object) session()->get('billing_address');
                    $billing_states = (new StateRepository())->getByCountryId($billing_address_check->country)->where('status', 1);
                    $billing_cities = (new CityRepository())->getByStateId($billing_address_check->state)->where('status', 1);
                }else{
                    $billing_states = (new StateRepository())->getByCountryId(app('general_setting')->default_country)->where('status', 1);
                    $billing_cities = (new CityRepository())->getByStateId(app('general_setting')->default_state)->where('status', 1);
                }
            }

            $infoCompleteOrder['states'] = $billing_states;
            $infoCompleteOrder['cities'] = $billing_cities;
            $infoCompleteOrder['billing_address'] = $billing_address;
            session()->put('infoCompleteOrder', $infoCompleteOrder);
            return view(theme('pages.payment_step'),$infoCompleteOrder);
        }

        if($step == 'complete_order'){
            $request->validate([
                'payment_id' => 'required',
                'gateway_id' => 'required',
                'step' => 'required'
            ]);
            $infoCompleteOrder = session()->get('infoCompleteOrder');
            $infoCompleteOrder['order_payment_id'] = decrypt($request->get('payment_id'));
            $infoCompleteOrder['order_gateway_id'] = decrypt($request->get('gateway_id'));

            $delivery_date = $infoCompleteOrder['delivery_date'];

            $grand_total = $infoCompleteOrder['total_amount'];
            $coupon = [];
            if(isset($infoCompleteOrder['coupon_amount'])){
                $grand_total = $grand_total - $infoCompleteOrder['coupon_amount'];
                $coupon = [
                    'coupon_amount' => $infoCompleteOrder['coupon_amount'],
                    'coupon_id' => $infoCompleteOrder['coupon_id']
                ];
            }
            if(!isModuleActive('MultiVendor')){
                if(isModuleActive('INTShipping') && app('theme')->folder_path == 'amazy'){
                    $shipping_method = $infoCompleteOrder['selected_shipping_method'];
                }else{
                    $shipping_method =$infoCompleteOrder['selected_shipping_method']->id;
                }
            }
            $orderData = [
                'grand_total' => $grand_total,
                'sub_total' => $infoCompleteOrder['subtotal_without_discount'],
                'discount_total' => $infoCompleteOrder['discount'],
                'number_of_item' => $infoCompleteOrder['number_of_item'],
                'number_of_package' => $infoCompleteOrder['number_of_package'],
                'shipping_cost' => $infoCompleteOrder['shipping_cost'],
                'shipping_method' => isModuleActive('MultiVendor') ? $infoCompleteOrder['selected_shipping_method'] : $shipping_method,
                'delivery_date' => $delivery_date,
                'order_payment_id' => decrypt($request->get('payment_id')),
                'payment_method' => decrypt($request->get('gateway_id')),
                'tax_total' => $infoCompleteOrder['tax_total'],
                'packagewiseTax' => $infoCompleteOrder['packagewise_tax'],
                'carts' => $infoCompleteOrder['cartData']
            ];
            $orderData = array_merge($orderData,$coupon);
            $request =$request->merge($orderData);
            // dd($request->all(),'ll');
            $orderController = App::make(OrderController::class);
            return $orderController->store($request);

        }

        $total_items = $this->checkoutService->totalAmountForPayment($cartData,null,null)['number_of_item'];
        $total_package = $this->checkoutService->totalAmountForPayment($cartData,null,null)['number_of_package'];
        $shipping_cost = $this->checkoutService->totalAmountForPayment($cartData,null,null)['shipping_cost'];
        $discount = $this->checkoutService->totalAmountForPayment($cartData,null,null)['discount'];
        $pickup_locations = $this->checkoutService->getActivePickup_loactions();
        $free_shipping_for_pickup_location = $this->checkoutService->freeShippingForPickup();

        if(isModuleActive('MultiVendor')){

           $package_wise_shipping = session()->get('package_wise_shipping');

           $session_packages = [];
            foreach ( $cartData as $seller_id => $packages){
                $additional_cost = 0;
                $totalItemPrice = 0;
                $totalItemWeight = 0;
                $totalItemBreadth = 0;
                $totalItemLength = 0;
                $totalItemHeight = 0;
                $package_cost = 0;
                $physical_count = 0;
                $item_in_cart = 0;

                foreach ($packages as $key => $item){
                    if($item->product_type == 'product' && $item->product->product->product->is_physical == 1){
                        if(sellerWiseShippingConfig($seller_id)['amount_multiply_with_qty']){
                            $additional_cost += ($item->product->sku->additional_shipping * $item->qty);
                        }else{
                            $additional_cost += $item->product->sku->additional_shipping;
                        }
                       $totalItemPrice += $item->total_price;
                       $totalItemWeight += !empty($item->product->sku->weight) ? $item->qty * $item->product->sku->weight : 0;
                       $totalItemHeight += $item->qty * $item->product->sku->height;
                       $totalItemLength += $item->qty * $item->product->sku->length;
                       $totalItemBreadth += $item->qty * $item->product->sku->breadth;
                       $physical_count += 1;
                       $item_in_cart += $item->qty;
                    }
                }

               if($package_wise_shipping && @$package_wise_shipping[$seller_id]['shipping_id']){
                   $shipping_method = ShippingMethod::with(['carrier'])->findOrFail($package_wise_shipping[$seller_id]['shipping_id']);
               }else{
                    $a_carriers = \Modules\Shipping\Entities\Carrier::where('type','Automatic')->whereHas('carrierConfigFrontend',function ($q) use ($seller_id){
                        $q->where('seller_id',$seller_id)->where('carrier_status',1);
                    });
                    $m_carriers = \Modules\Shipping\Entities\Carrier::where('type','Manual')->where('status', 1)->where('created_by',$seller_id);
                    if(sellerWiseShippingConfig(1)['seller_use_shiproket']){
                        $carriers = $a_carriers->unionAll($m_carriers)->get()->pluck('id')->toArray();
                    }else{
                        $carriers = $m_carriers->get()->pluck('id')->toArray();
                    }

                    $seller_shipping_methods = $shipping_methods->where('request_by_user',$seller_id)->whereIn('carrier_id',$carriers);
                    $total_check = 0;
                    $shipping_method = $shipping_methods->where('request_by_user',$seller_id)->whereIn('carrier_id',$carriers)->first();
                    foreach($seller_shipping_methods as $shipping_rate){
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
                                    $seller_shipping_cost = ($shipping_rate->cost * $item_in_cart) + $additional_cost;
                                }else{
                                    $seller_shipping_cost = $shipping_rate->cost + $additional_cost;
                                }
                            }else{
                                $seller_shipping_cost = 0;
                            }
                        }
                        $total_check = $totalItemPrice + $additional_cost + $seller_shipping_cost;
                        if($total_check >= $shipping_rate->minimum_shopping){
                            $shipping_method = $shipping_rate;
                            break;
                        }
                    }
               }

               if($shipping_method && $shipping_method->cost_based_on == 'Price'){
                   if($totalItemPrice > 0 && $shipping_method->cost > 0){
                       $package_cost = ($totalItemPrice / 100) *  $shipping_method->cost + $additional_cost;
                   }

               }elseif ($shipping_method && $shipping_method->cost_based_on == 'Weight'){
                   if($totalItemWeight > 0 && $shipping_method->cost > 0){
                       $package_cost = ($totalItemWeight / 100) *  $shipping_method->cost + $additional_cost;
                   }
               }else{
                   if($shipping_method && $shipping_method->cost > 0){
                        if(sellerWiseShippingConfig($seller_id)['amount_multiply_with_qty']){
                            $package_cost = ($shipping_method->cost * $item_in_cart) + $additional_cost;
                        }else{
                            $package_cost = $shipping_method->cost + $additional_cost;
                        }
                   }
               }
               if($physical_count > 0){
                if(isModuleActive('INTShipping') && app('theme')->folder_path == 'amazy'){
                    $shipping_id = '';
                    $shipping_method_name = '';
                    $shipping_time = '';
                }else{
                    $shipping_id = $shipping_method->id;
                    $shipping_method_name = $shipping_method->method_name;
                    $shipping_time = $shipping_method->shipment_time;
                }
                   $session_packages[$seller_id] = [
                       'seller_id'=>$seller_id,
                       'shipping_cost'=>$package_cost,
                       'additional_cost'=>$additional_cost,
                       'totalItemPrice'=>$totalItemPrice,
                       'totalItemWeight'=>$totalItemWeight,
                       'totalItemHeight'=>$totalItemHeight,
                       'totalItemLength'=>$totalItemLength,
                       'totalItemBreadth'=>$totalItemBreadth,
                       'shipping_id'=>$shipping_id,
                       'shipping_method'=>$shipping_method_name,
                       'shipping_time'=>$shipping_time,
                       'physical_count' => $physical_count,
                       'item_incart' => $item_in_cart
                   ];
               }else{
                    $email_shipping = \Modules\Shipping\Entities\ShippingMethod::first();
                    $session_packages[$seller_id] = [
                        'seller_id'=>$seller_id,
                        'shipping_cost'=> 0,
                        'additional_cost'=> 0,
                        'totalItemPrice'=>$totalItemPrice,
                        'totalItemWeight'=> 0,
                        'totalItemHeight'=> 0,
                        'totalItemLength'=> 0,
                        'totalItemBreadth'=> 0,
                        'shipping_id'=> 1,
                        'shipping_method'=>$email_shipping->method_name,
                        'shipping_time'=>$email_shipping->shipment_time,
                        'physical_count' => $physical_count
                    ];
               }

           }
           session()->forget('package_wise_shipping');
           session(['package_wise_shipping'=>$session_packages]);
       }else{
            session()->forget('single_package_height_weight_info');
            $totalItemWeight = 0;
            $totalItemBreadth = 0;
            $totalItemLength = 0;
            $totalItemHeight = 0;
            foreach ($cartData as $key => $item){
                if($item->product_type == 'product' && $item->product->product->product->is_physical == 1){
                    $totalItemWeight +=$item->qty * $item->product->sku->weight;
                    $totalItemHeight += $item->qty * $item->product->sku->height;
                    $totalItemLength += $item->qty * $item->product->sku->length;
                    $totalItemBreadth += $item->qty * $item->product->sku->breadth;
                }
            }
            $session_packages = [
                'totalItemWeight'=>$totalItemWeight,
                'totalItemHeight'=>$totalItemHeight,
                'totalItemLength'=>$totalItemLength,
                'totalItemBreadth'=>$totalItemBreadth,
            ];
            session(['single_package_height_weight_info'=>$session_packages]);
        }
        return view(theme('pages.checkout'),compact('shipping_methods','cartData','shipping_address',
            'gateway_activations','countries', 'giftCardExist', 'states', 'cities','total_items','total_package','shipping_cost','discount','pickup_locations','free_shipping_for_pickup_location'));
    }

    public function changeShippingMethod(Request $request)
    {
        $cartDataGroup = $this->checkoutService->getCartItem();

        $cartData = $cartDataGroup['cartData'];
        $giftCardExist = $cartDataGroup['gift_card_exist'];
        $customer = auth()->user();
        $shipping_address = null;
        $postalCodeRequired = false;
        if(isModuleActive('ShipRocket')){
            $postalCodeRequired = true;
        }
        if(auth()->check() && count(auth()->user()->customerAddresses) > 0){
            $shipping_address = auth()->user()->customerAddresses->where('is_shipping_default',1)->first();
            if($shipping_address){
                $states = (new StateRepository())->getByCountryId($shipping_address->country)->where('status', 1);
                $cities = (new CityRepository())->getByStateId($shipping_address->state)->where('status', 1);
            }else{
                $states = (new StateRepository())->getByCountryId(app('general_setting')->default_country)->where('status', 1);
                $cities = (new CityRepository())->getByStateId(app('general_setting')->default_state)->where('status', 1);
            }

        }else{
            if(session()->has('shipping_address')){
                $shipping_address = (object) session()->get('shipping_address');
            }
            $states = (new StateRepository())->getByCountryId(app('general_setting')->default_country)->where('status', 1);
            $cities = (new CityRepository())->getByStateId(app('general_setting')->default_state)->where('status', 1);
        }
        $countries = $this->checkoutService->getCountries();

        $gateway_activations = $this->checkoutService->getActivePaymentGetways();
        $shipping_methods = $this->checkoutService->get_active_shipping_methods();
        $total_items = $this->checkoutService->totalAmountForPayment($cartData,null,null)['number_of_item'];
        $total_package = $this->checkoutService->totalAmountForPayment($cartData,null,null)['number_of_package'];
        $shipping_cost = $this->checkoutService->totalAmountForPayment($cartData,null,null)['shipping_cost'];
        $discount = $this->checkoutService->totalAmountForPayment($cartData,null,null)['discount'];

        if(isModuleActive('MultiVendor')){

            $package_wise_shippings = session()->get('package_wise_shipping');

            $new_package_wise_shipping = [];

            foreach ($package_wise_shippings as $package_wise_shipping){
                if($package_wise_shipping['seller_id'] == $request->seller){
                    $shipping_method = ShippingMethod::with(['carrier'])->findOrFail($request->shipping_method);
                    $package_cost = 0;

                    if($shipping_method->cost_based_on == 'Price'){
                        if($package_wise_shipping['totalItemPrice'] > 0 && $shipping_method->cost > 0){
                            $package_cost = ($package_wise_shipping['totalItemPrice'] / 100) *  $shipping_method->cost + $package_wise_shipping['additional_cost'];
                        }

                    }elseif ($shipping_method->cost_based_on == 'Weight'){
                        if($package_wise_shipping['totalItemWeight'] > 0 && $shipping_method->cost > 0){
                            $package_cost = ($package_wise_shipping['totalItemWeight'] / 100) *  $shipping_method->cost + $package_wise_shipping['additional_cost'];
                        }
                    }else{
                        if($shipping_method->cost > 0){
                            if(sellerWiseShippingConfig($request->seller)['amount_multiply_with_qty']){
                                $package_cost = ($shipping_method->cost * $package_wise_shipping['item_incart']) + $package_wise_shipping['additional_cost'];
                            }else{
                                $package_cost = $shipping_method->cost + $package_wise_shipping['additional_cost'];
                            }
                        }else{
                            $package_cost = 0;
                        }
                    }
                    $new_package_wise_shipping[$request->seller] = [
                        'seller_id'=>$request->seller,
                        'shipping_cost'=>$package_cost,
                        'additional_cost'=>$package_wise_shipping['additional_cost'],
                        'totalItemPrice'=>$package_wise_shipping['totalItemPrice'],
                        'totalItemWeight'=>$package_wise_shipping['totalItemWeight'],
                        'shipping_id'=>$shipping_method->id,
                        'shipping_method'=>$shipping_method->method_name,
                        'shipping_time'=>$shipping_method->shipment_time,
                        'totalItemHeight'=>$package_wise_shipping['totalItemHeight'],
                        'totalItemLength'=>$package_wise_shipping['totalItemLength'],
                        'totalItemBreadth'=>$package_wise_shipping['totalItemBreadth'],
                        'physical_count' => $package_wise_shipping['physical_count'],
                        'item_incart' => $package_wise_shipping['item_incart']
                    ];
                }else{
                    $new_package_wise_shipping[$package_wise_shipping['seller_id']] = [
                        'seller_id'=>$package_wise_shipping['seller_id'],
                        'shipping_cost'=>$package_wise_shipping['shipping_cost'],
                        'additional_cost'=>$package_wise_shipping['additional_cost'],
                        'totalItemPrice'=>$package_wise_shipping['totalItemPrice'],
                        'totalItemWeight'=>$package_wise_shipping['totalItemWeight'],
                        'shipping_id'=>$package_wise_shipping['shipping_id'],
                        'shipping_method'=>$package_wise_shipping['shipping_method'],
                        'shipping_time'=>$package_wise_shipping['shipping_time'],
                        'totalItemHeight'=>$package_wise_shipping['totalItemHeight'],
                        'totalItemLength'=>$package_wise_shipping['totalItemLength'],
                        'totalItemBreadth'=>$package_wise_shipping['totalItemBreadth'],
                        'physical_count' => $package_wise_shipping['physical_count'],
                        'item_incart' => $package_wise_shipping['item_incart']
                    ];
                }



            }
            session()->forget('package_wise_shipping');
            session(['package_wise_shipping'=>$new_package_wise_shipping]);
        }

        return view(theme('partials._checkout_details'),compact('shipping_methods','cartData','shipping_address',
            'gateway_activations','countries', 'giftCardExist', 'states', 'cities','total_items','total_package','shipping_cost','discount', 'postalCodeRequired'));
    }

    public function destroy(Request $request){
        $this->checkoutService->deleteProduct($request->except('_token'));
        LogActivity::successLog('product delete by checkout successful.');
        return $this->reloadWithData();
    }

    public function shippingAddressChange(Request $request){
        $this->checkoutService->shippingAddressChange($request->except('_token'));
        LogActivity::successLog('Shipping address change successful.');
        return true;
    }
    public function billingAddressChange(Request $request){
        $address = auth()->user()->customerAddresses->where('id',$request->id)->first();
        if($address){
            $states = (new StateRepository())->getByCountryId($address->country)->where('status', 1);
            $cities = (new CityRepository())->getByStateId($address->state)->where('status', 1);
        }else{
            $states = collect();
            $cities = collect();
        }
        return response()->json([
            'address' => $address,
            'states' => $states,
            'cities' => $cities
        ],200);
    }

    public function couponApply(CouponApplyRequest $request){

        $coupon = Coupon::where('coupon_code',$request->coupon_code)->first();

        if(isset($coupon)){
            if(date('Y-m-d')>=$coupon->start_date && date('Y-m-d')<=$coupon->end_date){
                if($coupon->is_multiple_buy){
                    if($coupon->coupon_type == 1){
                        $carts = Cart::where('user_id',auth()->user()->id)->where('is_select',1)->where('product_type', 'product')->pluck('product_id');

                        $products = CouponProduct::where('coupon_id', $coupon->id)->whereHas('product',function($query) use($carts){
                            return $query->whereHas('skus', function($q) use($carts){
                                return $q->whereIn('id', $carts);
                            });
                        })->pluck('product_id');
                        if(count($products) > 0){
                            Session::put('coupon_type', $coupon->coupon_type);
                            Session::put('coupon_discount', $coupon->discount);
                            Session::put('coupon_discount_type', $coupon->discount_type);
                            Session::put('coupon_products', $products);
                            Session::put('coupon_id', $coupon->id);
                        }else{
                            return response()->json([
                                'error' => 'This Coupon is not available for selected products'
                            ]);
                        }

                    }elseif($coupon->coupon_type == 2){
                        if($request->shopping_amount < $coupon->minimum_shopping){
                            return response()->json([
                                'error' => 'You Have more purchase to get This Coupon.'
                            ]);
                        }else{
                            Session::put('coupon_type', $coupon->coupon_type);
                            Session::put('coupon_discount', $coupon->discount);
                            Session::put('coupon_discount_type', $coupon->discount_type);
                            Session::put('maximum_discount', $coupon->maximum_discount);
                            Session::put('coupon_id', $coupon->id);
                        }
                    }elseif($coupon->coupon_type == 3){
                        Session::put('coupon_type', $coupon->coupon_type);
                        Session::put('coupon_discount', $coupon->discount);
                        Session::put('coupon_discount_type', $coupon->discount_type);
                        Session::put('maximum_discount', $coupon->maximum_discount);
                        Session::put('coupon_id', $coupon->id);
                    }
                }else{
                    if(CouponUse::where('user_id',auth()->user()->id)->where('coupon_id',$coupon->id)->first() == null){
                        if($coupon->coupon_type == 1){
                            $carts = Cart::where('user_id',auth()->user()->id)->where('is_select',1)->where('product_type', 'product')->pluck('product_id');
                            $products = CouponProduct::where('coupon_id', $coupon->id)->whereHas('product',function($query) use($carts){
                                return $query->whereHas('skus', function($q) use($carts){
                                    return $q->whereIn('id', $carts);
                                });
                            })->pluck('product_id');

                            if(count($products) > 0){
                                Session::put('coupon_type', $coupon->coupon_type);
                                Session::put('coupon_discount', $coupon->discount);
                                Session::put('coupon_discount_type', $coupon->discount_type);
                                Session::put('coupon_products', $products);
                                Session::put('coupon_id', $coupon->id);
                            }else{
                                return response()->json([
                                    'error' => 'This Coupon is not available for selected products'
                                ]);
                            }

                        }elseif($coupon->coupon_type == 2){
                            if($request->shopping_amount < $coupon->minimum_shopping){
                                return response()->json([
                                    'error' => 'You Have more purchase to get This Coupon.'
                                ]);
                            }else{
                                Session::put('coupon_type', $coupon->coupon_type);
                                Session::put('coupon_discount', $coupon->discount);
                                Session::put('coupon_discount_type', $coupon->discount_type);
                                Session::put('maximum_discount', $coupon->maximum_discount);
                                Session::put('coupon_id', $coupon->id);
                            }

                        }elseif($coupon->coupon_type == 3){
                            Session::put('coupon_type', $coupon->coupon_type);
                            Session::put('coupon_discount', $coupon->discount);
                            Session::put('coupon_discount_type', $coupon->discount_type);
                            Session::put('maximum_discount', $coupon->maximum_discount);
                            Session::put('coupon_id', $coupon->id);
                        }

                    }else{
                        return response()->json([
                            'error' => 'This coupon already used'
                        ]);
                    }
                }
            }else{
                return response()->json([
                    'error' => 'coupon is expired'
                ]);
            }
        }else{
            return response()->json([
                'error' => 'invalid Coupon'
            ]);
        }
        return $this->reloadWithData();

    }
    public function couponDelete(){
        Session::forget('coupon_type');
        Session::forget('coupon_discount');
        Session::forget('coupon_discount_type');
        Session::forget('maximum_discount');
        Session::forget('maximum_products');
        Session::forget('coupon_id');
        return $this->reloadWithData();
    }

    private function couponCount($total_for_coupon,$shippingtotal){
        $coupon = 0;
        if(Session::has('coupon_type')&&Session::has('coupon_discount')){
            $coupon_type = Session::get('coupon_type');
            $coupon_discount = Session::get('coupon_discount');
            $coupon_discount_type = Session::get('coupon_discount_type');
            $coupon_id = Session::get('coupon_id');

            if($coupon_type == 1){
                $couponProducts = Session::get('coupon_products');
                if($coupon_discount_type == 0){

                    foreach($couponProducts as  $key => $item){
                        $cart = \App\Models\Cart::where('user_id',auth()->user()->id)->where('is_select',1)->where('product_type', 'product')->whereHas('product',function($query) use($item){
                            $query->whereHas('product', function($q) use($item){
                                $q->where('id', $item);
                            });
                        })->first();
                        $coupon += ($cart->total_price/100)* $coupon_discount;
                    }
                }else{
                    if($total_for_coupon > $coupon_discount){
                        $coupon = $coupon_discount;
                    }else {
                        $coupon = $total_for_coupon;
                    }
                }

            }
            elseif($coupon_type == 2){

                if($coupon_discount_type == 0){

                    $maximum_discount = Session::get('maximum_discount');
                    $coupon = ($total_for_coupon/100)* $coupon_discount;

                    if($coupon > $maximum_discount && $maximum_discount > 0){
                        $coupon = $maximum_discount;
                    }
                }else{
                    $coupon = $coupon_discount;
                }
            }
            elseif($coupon_type == 3){
                $maximum_discount = Session::get('maximum_discount');
                $coupon = $shippingtotal;

                if($coupon > $maximum_discount && $maximum_discount > 0){
                    $coupon = $maximum_discount;
                }

            }
        }
        return [
            'coupon_amount' => $coupon,
            'coupon_id' => $coupon_id
        ];
    }


    private function reloadWithData()
    {
        $cartDataGroup = $this->checkoutService->getCartItem();
        $cartData = $cartDataGroup['cartData'];
        $giftCardExist = $cartDataGroup['gift_card_exist'];
        
        $infoCompleteOrder  = session()->get('infoCompleteOrder');
        $infoCompleteOrder['countries'] = $this->checkoutService->getCountries();

        if(auth()->check() && count(auth()->user()->customerAddresses) > 0){
            $billing_address_check = auth()->user()->customerAddresses->where('is_billing_default',1)->where('is_shipping_default',0)->first();
            if($billing_address_check){
                $billing_states = (new StateRepository())->getByCountryId($billing_address_check->country)->where('status', 1);
                $billing_cities = (new CityRepository())->getByStateId($billing_address_check->state)->where('status', 1);
            }else{
                $billing_states = (new StateRepository())->getByCountryId(app('general_setting')->default_country)->where('status', 1);
                $billing_cities = (new CityRepository())->getByStateId(app('general_setting')->default_state)->where('status', 1);
            }

        }else{
            if(session()->has('billing_address')){
                $billing_address_check = (object) session()->get('billing_address');
                $billing_states = (new StateRepository())->getByCountryId($billing_address_check->country)->where('status', 1);
                $billing_cities = (new CityRepository())->getByStateId($billing_address_check->state)->where('status', 1);
            }else{
                $billing_states = (new StateRepository())->getByCountryId(app('general_setting')->default_country)->where('status', 1);
                $billing_cities = (new CityRepository())->getByStateId(app('general_setting')->default_state)->where('status', 1);
            }
        }

        $infoCompleteOrder['states'] = $billing_states;
        $infoCompleteOrder['cities'] = $billing_cities;

        $infoCompleteOrder['billing_address'] = $this->checkoutService->activeBillingAddress();
        $address = $this->checkoutService->activeShippingAddress();
        $coupon = [];
        if(isModuleActive('MultiVendor')){
            $totalAmountForPayment = $this->checkoutService->totalAmountForPayment($cartData,null,$address);
            $subtotal_without_discount = $totalAmountForPayment['subtotal'];
            $shipping_cost = $totalAmountForPayment['shipping_cost'];
            $discount = $totalAmountForPayment['discount'];
            $total_amount = $totalAmountForPayment['grand_total'];
            if(Session::has('coupon_type') && Session::has('coupon_discount')){
                $coupon = $this->couponCount($subtotal_without_discount-$discount, collect($shipping_cost)->sum());
                $total_amount = $infoCompleteOrder['total_amount'];
            }else{
                $infoCompleteOrder['total_amount'] = $total_amount;
                unset($infoCompleteOrder['coupon_amount']);
                unset($infoCompleteOrder['coupon_id']);
            }
        }else{
            $selected_shipping_method = $infoCompleteOrder['selected_shipping_method'];
            $totalAmountForPayment = $this->checkoutService->totalAmountForPayment($cartData,$selected_shipping_method,$address);
            $subtotal_without_discount = $totalAmountForPayment['subtotal'];
            $discount = $totalAmountForPayment['discount'];
            $shipping_cost = $totalAmountForPayment['shipping_cost'];
            $total_amount = $totalAmountForPayment['grand_total'];
            if(Session::has('coupon_type')&& Session::has('coupon_discount')){
                $coupon = $this->couponCount($subtotal_without_discount-$discount,$shipping_cost);
                $total_amount = $infoCompleteOrder['total_amount'];
                $infoCompleteOrder['total_amount'] = $total_amount;
            }else{
                $infoCompleteOrder['total_amount'] = $total_amount;
                unset($infoCompleteOrder['coupon_amount']);
                unset($infoCompleteOrder['coupon_id']);
            }
        }

        $infoCompleteOrder = array_merge($infoCompleteOrder,$coupon);
        session()->put('infoCompleteOrder', $infoCompleteOrder);
        return response()->json([
            'MainCheckout' =>  (string)view(theme('partials._payment_step_details'),$infoCompleteOrder)
        ]);
    }

    public function billingAddressStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|max:255',
            'phone' => 'required|max:30',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'address' => 'required'
        ]);
        try {
            $result = $this->checkoutService->billingAddressStore($request->except('_token'));
            if($result === 1){
                return response()->json([
                    'msg' => 'success'
                ],200);
            }
            return response()->json([
                'msg' => 'error'
            ],500);

        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'msg' => 'error'
            ],500);
        }

    }

    public function shippingAddressStore(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|max:255',
            'phone' => 'required|max:30',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'address' => 'required'
        ]);
        try {
            $result = $this->checkoutService->shippingAddressStore($request->except('_token'));
            
            if($result === 1){
                return response()->json([
                    'msg' => 'success'
                ],200);
            }
            return response()->json([
                'msg' => 'error'
            ],500);

        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'msg' => 'error'
            ],500);
        }
    }

    public function checkCartPriceUpdate(Request $request){
        $result = $this->checkoutService->checkCartPriceUpdate();
        return response()->json([
            'count' => $result,
            'msg' => 'success'
        ],200);
    }

}
