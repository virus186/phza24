<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreateRequest;
use App\Models\Order;
use App\Models\OrderPackageDetail;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Refund\Entities\RefundRequest;
use App\Services\ProductReviewService;
use Modules\Refund\Repositories\RefundReasonRepository;
use Modules\Refund\Services\RefundService;
use Modules\Shipping\Repositories\ShippingRepository;

/**
* @group Order Manage
*
* APIs for customer Order
*/
class OrderController extends Controller
{
    protected $orderService;
    protected $productReviewService;
    protected $refundService;

    public function __construct(OrderService $orderService, ProductReviewService $productReviewService, RefundService $refundService)
    {
        $this->orderService = $orderService;
        $this->productReviewService = $productReviewService;
        $this->refundService = $refundService;
    }

    /**
     * All Order list
     * @response{
     * "orders": [
     *       {
     *           "id": 5,
     *           "customer_id": 5,
     *           "order_payment_id": null,
     *           "order_type": null,
     *           "order_number": "order-6726-210607071843",
     *           "payment_type": 1,
     *           "is_paid": 1,
     *           "is_confirmed": 1,
     *           "is_completed": 1,
     *           "is_cancelled": 0,
     *           "customer_email": "customer1@gmail.com",
     *           "customer_phone": "016859865968",
     *           "customer_shipping_address": 1,
     *           "customer_billing_address": 3,
     *           "number_of_package": 1,
     *           "grand_total": 397.2,
     *           "sub_total": 440,
     *           "discount_total": 88,
     *           "shipping_total": 10,
     *           "number_of_item": 2,
     *           "order_status": 5,
     *           "tax_amount": 17.6,
     *           "created_at": "2021-06-07T13:18:43.000000Z",
     *           "updated_at": "2021-06-08T08:51:16.000000Z",
     *           "customer": {
     *               
     *           },
     *           "packages": [
     *               {
     *                   "id": 8,
     *                   "order_id": 5,
     *                   "seller_id": 4,
     *                   "package_code": "TRK - 13620585",
     *                   "number_of_product": 1,
     *                   "shipping_cost": 10,
     *                   "shipping_date": "deafultTheme.Est_arrival_date: 15 Jun - 19 Jun",
     *                   "shipping_method": 3,
     *                   "is_cancelled": 0,
     *                   "is_reviewed": 1,
     *                   "delivery_status": 5,
     *                   "last_updated_by": 4,
     *                   "tax_amount": 17.6,
     *                   "created_at": "2021-06-07T13:18:43.000000Z",
     *                   "updated_at": "2021-06-08T08:51:16.000000Z",
     *                   "deliveryStateName": "Pending",
     *                   "products": [
     *                       
     *                   ]
     *               }
     *           ]
     *       }
     *       
     *   ],
     *   "message": "success"
     * }
     */
    
    public function allOrderList(Request $request){

        $orders = Order::with('customer', 'packages','address.getShippingCountry','address.getShippingState','address.getShippingCity','address.getBillingCountry','address.getBillingState','address.getBillingCity','packages.seller','packages.delivery_states', 'shipping_address','billing_address', 'packages.products','packages.products.seller_product_sku.product.product',
        'packages.products.seller_product_sku.product_variations.attribute','packages.products.seller_product_sku.product_variations.attribute_value.color','packages.products.giftCard','packages.products.seller_product_sku.sku')->where('customer_id', $request->user()->id)->latest()->get();
        
        if(count($orders) > 0){
            return response()->json([
                'orders' => $orders,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'order not found'
            ]);
        }

    }
    

    /**
     * Pending Order list
     * @response{
     *      "orders": [
     *       {
     *           "id": 5,
     *           "customer_id": 5,
     *           "order_payment_id": null,
     *           "order_type": null,
     *           "order_number": "order-6726-210607071843",
     *           "payment_type": 1,
     *           "is_paid": 1,
     *           "is_confirmed": 0,
     *           "is_completed": 0,
     *           "is_cancelled": 0,
     *           "customer_email": "customer1@gmail.com",
     *           "customer_phone": "016859865968",
     *           "customer_shipping_address": 1,
     *           "customer_billing_address": 3,
     *           "number_of_package": 1,
     *           "grand_total": 397.2,
     *           "sub_total": 440,
     *           "discount_total": 88,
     *           "shipping_total": 10,
     *           "number_of_item": 2,
     *           "order_status": 5,
     *           "tax_amount": 17.6,
     *           "created_at": "2021-06-07T13:18:43.000000Z",
     *           "updated_at": "2021-06-08T08:51:16.000000Z",
     *           "customer": {
     *               
     *           },
     *           "packages": [
     *               {
     *                   "id": 8,
     *                   "order_id": 5,
     *                   "seller_id": 4,
     *                   "package_code": "TRK - 13620585",
     *                   "number_of_product": 1,
     *                   "shipping_cost": 10,
     *                   "shipping_date": "deafultTheme.Est_arrival_date: 15 Jun - 19 Jun",
     *                   "shipping_method": 3,
     *                   "is_cancelled": 0,
     *                   "is_reviewed": 1,
     *                   "delivery_status": 5,
     *                   "last_updated_by": 4,
     *                   "tax_amount": 17.6,
     *                   "created_at": "2021-06-07T13:18:43.000000Z",
     *                   "updated_at": "2021-06-08T08:51:16.000000Z",
     *                   "deliveryStateName": "Pending",
     *                   "products": [
     *                       
     *                   ]
     *               }
     *           ]
     *       }
     *       
     *   ],
     *   "message": "success"
     * }
     */

    public function PendingOrderList(Request $request){

        $orders = Order::with('customer', 'packages','address.getShippingCountry','address.getShippingState','address.getShippingCity','address.getBillingCountry','address.getBillingState','address.getBillingCity','packages.seller','packages.delivery_states', 'shipping_address','billing_address', 'packages.products','packages.products.seller_product_sku.product.product','packages.products.seller_product_sku.product_variations.attribute','packages.products.seller_product_sku.product_variations.attribute_value.color','packages.products.giftCard', 'packages.products.seller_product_sku.sku')->where('customer_id', $request->user()->id)->where('is_confirmed', 0)->latest()->get();
        if(count($orders) > 0){
            return response()->json([
                'orders' => $orders,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'order not found'
            ]);
        }
    }

    /**
     * Cancel Order list
     * @response{
     *      "orders": [
     *       {
     *           "id": 5,
     *           "customer_id": 5,
     *           "order_payment_id": null,
     *           "order_type": null,
     *           "order_number": "order-6726-210607071843",
     *           "payment_type": 1,
     *           "is_paid": 0,
     *           "is_confirmed": 0,
     *           "is_completed": 0,
     *           "is_cancelled": 1,
     *           "customer_email": "customer1@gmail.com",
     *           "customer_phone": "016859865968",
     *           "customer_shipping_address": 1,
     *           "customer_billing_address": 3,
     *           "number_of_package": 1,
     *           "grand_total": 397.2,
     *           "sub_total": 440,
     *           "discount_total": 88,
     *           "shipping_total": 10,
     *           "number_of_item": 2,
     *           "order_status": 5,
     *           "tax_amount": 17.6,
     *           "created_at": "2021-06-07T13:18:43.000000Z",
     *           "updated_at": "2021-06-08T08:51:16.000000Z",
     *           "customer": {
     *               
     *           },
     *           "packages": [
     *               {
     *                   "id": 8,
     *                   "order_id": 5,
     *                   "seller_id": 4,
     *                   "package_code": "TRK - 13620585",
     *                   "number_of_product": 1,
     *                   "shipping_cost": 10,
     *                   "shipping_date": "deafultTheme.Est_arrival_date: 15 Jun - 19 Jun",
     *                   "shipping_method": 3,
     *                   "is_cancelled": 0,
     *                   "is_reviewed": 1,
     *                   "delivery_status": 5,
     *                   "last_updated_by": 4,
     *                   "tax_amount": 17.6,
     *                   "created_at": "2021-06-07T13:18:43.000000Z",
     *                   "updated_at": "2021-06-08T08:51:16.000000Z",
     *                   "deliveryStateName": "Pending",
     *                   "products": [
     *                       
     *                   ]
     *               }
     *           ]
     *       }
     *       
     *   ],
     *   "message": "success"
     * }
     */

    public function cancelOrderList(Request $request){

        $orders = Order::with('customer', 'packages','address.getShippingCountry','address.getShippingState','address.getShippingCity','address.getBillingCountry','address.getBillingState','address.getBillingCity',
        'packages.seller','packages.delivery_states', 'shipping_address','billing_address', 'packages.products','packages.products.seller_product_sku.product.product','packages.products.seller_product_sku.product_variations.attribute','packages.products.seller_product_sku.product_variations.attribute_value.color','packages.products.giftCard', 'packages.products.seller_product_sku.sku')->where('customer_id', $request->user()->id)->where('is_cancelled', 1)->latest()->get();
        if(count($orders) > 0){
            return response()->json([
                'orders' => $orders,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'order not found'
            ]);
        }
    }

    /**
     * Order To Ship
     * @response{
     *    "packages": [
     *       {
     *           "id": 8,
     *           "order_id": 5,
     *           "seller_id": 4,
     *           "package_code": "TRK - 13620585",
     *           "number_of_product": 1,
     *           "shipping_cost": 10,
     *           "shipping_date": "deafultTheme.Est_arrival_date: 15 Jun - 19 Jun",
     *           "shipping_method": 3,
     *           "is_cancelled": 0,
     *           "is_reviewed": 0,
     *           "delivery_status": 3,
     *           "last_updated_by": 4,
     *           "tax_amount": 17.6,
     *           "created_at": "2021-06-07T13:18:43.000000Z",
     *           "updated_at": "2021-06-08T08:51:16.000000Z",
     *           "deliveryStateName": "ship",
     *           "order": {
     *               "id": 5,
     *               "customer_id": 5,
     *               "order_payment_id": null,
     *               "order_type": null,
     *               "order_number": "order-6726-210607071843",
     *               "payment_type": 1,
     *               "is_paid": 1,
     *               "is_confirmed": 1,
     *               "is_completed": 0,
     *               "is_cancelled": 0,
     *               "customer_email": "customer1@gmail.com",
     *               "customer_phone": "016859865968",
     *               "customer_shipping_address": 1,
     *               "customer_billing_address": 3,
     *               "number_of_package": 1,
     *               "grand_total": 397.2,
     *               "sub_total": 440,
     *               "discount_total": 88,
     *               "shipping_total": 10,
     *               "number_of_item": 2,
     *               "order_status": 5,
     *               "tax_amount": 17.6,
     *               "created_at": "2021-06-07T13:18:43.000000Z",
     *               "updated_at": "2021-06-08T08:51:16.000000Z"
     *           }
     *       }
     *   ],
     *   "message": "success
     * }
     */

    public function orderToShip(Request $request){
        $packages = $this->orderService->getOrderToShip($request->user()->id);

        if(count($packages) > 0){
            return response()->json([
                'packages' => $packages,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'package not found'
            ],404);
        }
    }

    /**
     * Order To Receive
     * @response{
     *    "packages": [
     *       {
     *           "id": 8,
     *           "order_id": 5,
     *           "seller_id": 4,
     *           "package_code": "TRK - 13620585",
     *           "number_of_product": 1,
     *           "shipping_cost": 10,
     *           "shipping_date": "deafultTheme.Est_arrival_date: 15 Jun - 19 Jun",
     *           "shipping_method": 3,
     *           "is_cancelled": 0,
     *           "is_reviewed": 0,
     *           "delivery_status": 4,
     *           "last_updated_by": 4,
     *           "tax_amount": 17.6,
     *           "created_at": "2021-06-07T13:18:43.000000Z",
     *           "updated_at": "2021-06-08T08:51:16.000000Z",
     *           "deliveryStateName": "receive",
     *           "order": {
     *               "id": 5,
     *               "customer_id": 5,
     *               "order_payment_id": null,
     *               "order_type": null,
     *               "order_number": "order-6726-210607071843",
     *               "payment_type": 1,
     *               "is_paid": 1,
     *               "is_confirmed": 1,
     *               "is_completed": 0,
     *               "is_cancelled": 0,
     *               "customer_email": "customer1@gmail.com",
     *               "customer_phone": "016859865968",
     *               "customer_shipping_address": 1,
     *               "customer_billing_address": 3,
     *               "number_of_package": 1,
     *               "grand_total": 397.2,
     *               "sub_total": 440,
     *               "discount_total": 88,
     *               "shipping_total": 10,
     *               "number_of_item": 2,
     *               "order_status": 5,
     *               "tax_amount": 17.6,
     *               "created_at": "2021-06-07T13:18:43.000000Z",
     *               "updated_at": "2021-06-08T08:51:16.000000Z"
     *           }
     *       }
     *   ],
     *   "message": "success
     * }
     */

    public function orderToReceive(Request $request){
        $packages = $this->orderService->getOrderToReceive($request->user()->id);
        if(count($packages) > 0){
            return response()->json([
                'packages' => $packages,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'package not found'
            ],404);
        }
    }

    /**
     * Order Store
     * @response{
     *      'message' : 'order created successfully'
     * }
     */

    public function orderStore(Request $request){
        $request->validate([
            'customer_email' => 'required',
            'customer_phone' => 'required',
            'payment_method' => 'required',
            'customer_shipping_address' => 'required',
            'customer_billing_address' => 'required',
            'grand_total' => 'required',
            'sub_total' => 'required',
            'discount_total' => 'required',
            'shipping_total' => 'required',
            'number_of_package' => 'required',
            'number_of_item' => 'required',
            'payment_id' => 'required',
            'tax_total' => 'required',
            'shipping_cost.*' => 'required',
            'delivery_date.*' => 'required',
            'shipping_method.*' => 'required',
            'packagewiseTax.*' => 'required',
            'payment_method' => 'required',
        ]);
        try{
            DB::beginTransaction();
            $order = $this->orderService->orderStoreForAPI($request->user(), $request->except('_token'));
            DB::commit();
            return response()->json([
                'message' => 'order created successfully'
            ],201);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'something gone wrong'
            ], 503);
        }
        

    }

    /**
     * Single Order
     * @response{
     *      "order":{
     *           "id": 5,
     *           "customer_id": 5,
     *           "order_payment_id": null,
     *           "order_type": null,
     *           "order_number": "order-6726-210607071843",
     *           "payment_type": 1,
     *           "is_paid": 0,
     *           "is_confirmed": 0,
     *           "is_completed": 0,
     *           "is_cancelled": 1,
     *           "customer_email": "customer1@gmail.com",
     *           "customer_phone": "016859865968",
     *           "customer_shipping_address": 1,
     *           "customer_billing_address": 3,
     *           "number_of_package": 1,
     *           "grand_total": 397.2,
     *           "sub_total": 440,
     *           "discount_total": 88,
     *           "shipping_total": 10,
     *           "number_of_item": 2,
     *           "order_status": 5,
     *           "tax_amount": 17.6,
     *           "created_at": "2021-06-07T13:18:43.000000Z",
     *           "updated_at": "2021-06-08T08:51:16.000000Z",
     *           "customer": {
     *               
     *           },
     *           "packages": [
     *               {
     *                   "id": 8,
     *                   "order_id": 5,
     *                   "seller_id": 4,
     *                   "package_code": "TRK - 13620585",
     *                   "number_of_product": 1,
     *                   "shipping_cost": 10,
     *                   "shipping_date": "deafultTheme.Est_arrival_date: 15 Jun - 19 Jun",
     *                   "shipping_method": 3,
     *                   "is_cancelled": 0,
     *                   "is_reviewed": 1,
     *                   "delivery_status": 5,
     *                   "last_updated_by": 4,
     *                   "tax_amount": 17.6,
     *                   "created_at": "2021-06-07T13:18:43.000000Z",
     *                   "updated_at": "2021-06-08T08:51:16.000000Z",
     *                   "deliveryStateName": "Pending",
     *                   "products": [
     *                       
     *                   ]
     *               }
     *           ]
     *   },
     *   "message": "success"
     * }
     */

    public function singleOrder(Request $request, $order_number){

        $order = Order::with('customer', 'packages', 'packages.seller','address.getShippingCountry','address.getShippingState','address.getShippingCity','address.getBillingCountry','address.getBillingState','address.getBillingCity','packages.delivery_states', 'shipping_address','billing_address', 'packages.products','packages.products.seller_product_sku.product.product','packages.products.seller_product_sku.product_variations.attribute','packages.products.seller_product_sku.product_variations.attribute_value.color','packages.products.giftCard', 'packages.products.seller_product_sku.sku')->where('customer_id', $request->user()->id)->where('order_number', $request->order_number)->first();
        if($order){
            return response()->json([
                'order' => $order,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'order not found'
            ],404);
        }
    }

    /**
     * Refund Order List
     * @response{
     *      "refundOrders": [
     *           {
     *               "id": 2,
     *               "customer_id": 5,
     *               "order_id": 5,
     *               "refund_method": "wallet",
     *               "shipping_method": "courier",
     *               "shipping_method_id": 3,
     *               "pick_up_address_id": 1,
     *               "drop_off_address": null,
     *               "additional_info": "test for refund",
     *               "total_return_amount": 352,
     *               "refund_state": 0,
     *               "is_confirmed": 0,
     *               "is_refunded": 0,
     *               "is_completed": 0,
     *               "created_at": "2021-06-09T05:34:20.000000Z",
     *               "updated_at": "2021-06-09T05:34:20.000000Z",
     *               "CheckConfirmed": "Pending",
     *               "order": {
     *                   "id": 5,
     *                   "customer_id": 5,
     *                   "order_payment_id": null,
     *                   "order_type": null,
     *                   "order_number": "order-6726-210607071843",
     *                   "payment_type": 1,
     *                   "is_paid": 1,
     *                   "is_confirmed": 1,
     *                   "is_completed": 1,
     *                   "is_cancelled": 0,
     *                   "customer_email": "customer1@gmail.com",
     *                   "customer_phone": "016859865968",
     *                   "customer_shipping_address": 1,
     *                   "customer_billing_address": 3,
     *                   "number_of_package": 1,
     *                   "grand_total": 397.2,
     *                   "sub_total": 440,
     *                   "discount_total": 88,
     *                   "shipping_total": 10,
     *                   "number_of_item": 2,
     *                   "order_status": 5,
     *                   "tax_amount": 17.6,
     *                   "created_at": "2021-06-07T13:18:43.000000Z",
     *                   "updated_at": "2021-06-08T08:51:16.000000Z"
     *               },
     *               "shipping_gateway": {
     *                   "id": 3,
     *                   "method_name": "Free Shipping",
     *                   "logo": null,
     *                   "phone": "0356865656546",
     *                   "shipment_time": "8-12 days",
     *                   "cost": 0,
     *                   "is_active": 1,
     *                   "created_at": "2021-05-29T07:34:51.000000Z",
     *                   "updated_at": "2021-05-29T07:34:51.000000Z"
     *               },
     *               "pick_up_address_customer": {
     *                   "id": 1,
     *                   "customer_id": 5,
     *                   "name": "customer 1",
     *                   "email": "customer1@gmail.com",
     *                   "phone": "016859865968",
     *                   "address": "dhaka, bangladesh",
     *                   "city": "7291",
     *                   "state": "348",
     *                   "country": "18",
     *                   "postal_code": "6568656",
     *                   "is_shipping_default": 1,
     *                   "is_billing_default": 0,
     *                   "created_at": "2021-05-29T12:06:24.000000Z",
     *                   "updated_at": "2021-06-06T11:40:15.000000Z"
     *               },
     *               "refund_details": [
     *                   {
     *                       "id": 3,
     *                       "refund_request_id": 2,
     *                       "order_package_id": 8,
     *                       "seller_id": 4,
     *                       "processing_state": 0,
     *                       "created_at": "2021-06-09T05:34:20.000000Z",
     *                       "updated_at": "2021-06-09T05:34:20.000000Z",
     *                       "ProcessState": "Pending",
     *                       "order_package": {
     *                           "id": 8,
     *                           "order_id": 5,
     *                           "seller_id": 4,
     *                           "package_code": "TRK - 13620585",
     *                           "number_of_product": 1,
     *                           "shipping_cost": 10,
     *                           "shipping_date": "deafultTheme.Est_arrival_date: 15 Jun - 19 Jun",
     *                           "shipping_method": 3,
     *                           "is_cancelled": 0,
     *                           "is_reviewed": 1,
     *                           "delivery_status": 5,
     *                           "last_updated_by": 4,
     *                           "tax_amount": 17.6,
     *                           "created_at": "2021-06-07T13:18:43.000000Z",
     *                           "updated_at": "2021-06-08T08:51:16.000000Z",
     *                           "deliveryStateName": "Pending"
     *                       },
     *                       "seller": {
     *                           "id": 4,
     *                           "first_name": "Amazcart Ltd",
     *                           "last_name": null,
     *                           "username": "0156356563235",
     *                           "photo": null,
     *                           "role_id": 5,
     *                           "mobile_verified_at": null,
     *                           "email": "amazcart@gmail.com",
     *                           "is_verified": 1,
     *                           "verify_code": "74d68bde279426442de115eb532f9f51a21eb448",
     *                           "email_verified_at": null,
     *                           "notification_preference": "mail",
     *                           "is_active": 1,
     *                           "avatar": null,
     *                           "phone": null,
     *                           "date_of_birth": null,
     *                           "description": null,
     *                           "secret_login": 0,
     *                           "secret_logged_in_by_user": null,
     *                           "created_at": "2021-05-29T07:15:56.000000Z",
     *                           "updated_at": "2021-05-29T07:15:56.000000Z"
     *                       },
     *                       "process_refund": null,
     *                       "refund_products": [
     *                           
     *                       ]
     *                   }
     *               ]
     *           }
     *       ],
     *       "message": "success"
     * }
     */

    public function refundOrderList(Request $request){
        $refundOrders = RefundRequest::with('order','shipping_gateway','pick_up_address_customer','refund_details','refund_details.order_package','refund_details.seller',
        'refund_details.process_refund','refund_details.refund_products','refund_details.refund_products.seller_product_sku','refund_details.refund_products.seller_product_sku.product_variations.attribute','refund_details.refund_products.seller_product_sku.product_variations.attribute_value.color','refund_details.refund_products.seller_product_sku.product',
        'refund_details.refund_products.seller_product_sku.product.product')
        ->where('customer_id', $request->user()->id)->latest()->get();

        if(count($refundOrders) > 0){
            return response()->json([
                'refundOrders' => $refundOrders,
                'message' => 'success'
            ], 200);
        }else{
            return response()->json([
                'message' => 'not found'
            ]);
        }

    }

    /**
     * Order Track
     * @bodyParam order_number string required order number
     * @bodyParam phone string required billing phone number rewuired for registerd user
     * @bodyParam secret_id string required required for guest
     * @response{
     *      "order": {
     *          "id": 2,
     *          "customer_id": 5,
     *          "order_payment_id": null,
     *          "order_type": null,
     *          "order_number": "order-6281-210529061127",
     *          "payment_type": 1,
     *          "is_paid": 1,
     *          "is_confirmed": 1,
     *          "is_completed": 1,
     *          "is_cancelled": 0,
     *          "customer_email": "customer1@gmail.com",
     *          "customer_phone": "016859865968",
     *          "customer_shipping_address": 1,
     *          "customer_billing_address": 1,
     *          "number_of_package": 2,
     *          "grand_total": 8695.8,
     *          "sub_total": 6720,
     *          "discount_total": 61,
     *          "shipping_total": 60,
     *          "number_of_item": 2,
     *          "order_status": 4,
     *          "tax_amount": 977.95,
     *          "created_at": "2021-05-29T12:11:27.000000Z",
     *          "updated_at": "2021-06-08T07:48:34.000000Z",
     *          "customer": {
     *              
     *          },
     *          "packages": [
     *              {
     *                  "id": 2,
     *                  "order_id": 2,
     *                  "seller_id": 2,
     *                  "package_code": "TRK - 37774243",
     *                  "number_of_product": 1,
     *                  "shipping_cost": 50,
     *                  "shipping_date": "deafultTheme.Est_arrival_date: 29 May - 01 Jun",
     *                  "shipping_method": 2,
     *                  "is_cancelled": 0,
     *                  "is_reviewed": 0,
     *                  "delivery_status": 4,
     *                  "last_updated_by": null,
     *                  "tax_amount": 967.5,
     *                  "created_at": "2021-05-29T12:11:27.000000Z",
     *                  "updated_at": "2021-06-08T07:48:34.000000Z",
     *                  "deliveryStateName": "Recieved",
     *                  "products": [
     *                      {
     *                          "id": 1,
     *                          "package_id": 2,
     *                          "type": "product",
     *                          "product_sku_id": 7,
     *                          "qty": 1,
     *                          "price": 6450,
     *                          "total_price": 6450,
     *                          "tax_amount": 1,
     *                          "created_at": "2021-05-29T12:11:27.000000Z",
     *                          "updated_at": "2021-05-29T12:11:27.000000Z"
     *                      }
     *                  ]
     *              },
     *              {
     *                  "id": 3,
     *                  "order_id": 2,
     *                  "seller_id": 3,
     *                  "package_code": "TRK - 18153972",
     *                  "number_of_product": 1,
     *                  "shipping_cost": 10,
     *                  "shipping_date": "deafultTheme.Est_arrival_date: 06 Jun - 10 Jun",
     *                  "shipping_method": 3,
     *                  "is_cancelled": 0,
     *                  "is_reviewed": 0,
     *                  "delivery_status": 0,
     *                  "last_updated_by": null,
     *                  "tax_amount": 10.45,
     *                  "created_at": "2021-05-29T12:11:27.000000Z",
     *                  "updated_at": "2021-05-29T12:11:27.000000Z",
     *                  "deliveryStateName": "Pending",
     *                  "products": [
     *                      {
     *                          "id": 2,
     *                          "package_id": 3,
     *                          "type": "product",
     *                          "product_sku_id": 4,
     *                          "qty": 1,
     *                          "price": 209,
     *                          "total_price": 209,
     *                          "tax_amount": 1,
     *                          "created_at": "2021-05-29T12:11:27.000000Z",
     *                          "updated_at": "2021-05-29T12:11:27.000000Z"
     *                      }
     *                  ]
     *              }
     *          ]
     *      },
     *      "message": "success"
     * }
     */

    public function orderTrack(Request $request){

        $user = $request->user();
        $request->validate([
            'order_number' => 'required',
            'secret_id' => (!$user) ? 'required' : 'nullable',
            'phone' => ($user) ? 'required' : 'nullable',
        ]);
        try {
            if($user){
                $data['order'] = $this->orderService->orderFindByOrderNumber($request->except('_token'), $user);
            }else{
                $data['order'] = $this->orderService->orderFindByOrderNumber($request->except('_token'), null);
            }
            
            if ($data['order'] == "Invalid Tracking ID") {
                
                return response()->json([
                    'message' => $data['order'] 
                ],200);
            }
            elseif ($data['order'] == "Invalid Secret ID") {
                return response()->json([
                    'message' => $data['order'] 
                ],200);
            }
            elseif ($data['order'] == "Phone Number didn't match") {
                return response()->json([
                    'message' => $data['order']
                ],409);
            }
            else {
                return response()->json([
                    'order' => $data['order'],
                    'message' => 'success'
                ],200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'something gone wrong'
            ],503);
        }
    }

    /**
     * Order Review Package wise
     * @urlParam order_id integer required order id
     * @urlParam seller_id integer required seller id
     * @urlParam package_id integer required package id
     * @response{
     *      "package": {
     *           "id": 8,
     *           "order_id": 5,
     *           "seller_id": 4,
     *           "package_code": "TRK - 13620585",
     *           "number_of_product": 1,
     *           "shipping_cost": 10,
     *           "shipping_date": "deafultTheme.Est_arrival_date: 15 Jun - 19 Jun",
     *           "shipping_method": 3,
     *           "is_cancelled": 0,
     *           "is_reviewed": 1,
     *           "delivery_status": 5,
     *           "last_updated_by": 4,
     *           "tax_amount": 17.6,
     *           "created_at": "2021-06-07T13:18:43.000000Z",
     *           "updated_at": "2021-06-08T08:51:16.000000Z",
     *           "deliveryStateName": "Pending",
     *           "order": {
     *               "id": 5,
     *               "customer_id": 5,
     *               "order_payment_id": null,
     *               "order_type": null,
     *               "order_number": "order-6726-210607071843",
     *               "payment_type": 1,
     *               
     *           },
     *           "products": [
     *               
     *           ],
     *           "shipping": {
     *               "id": 3,
     *               "method_name": "Free Shipping",
     *               "logo": null,
     *               "phone": "0356865656546",
     *               "shipment_time": "8-12 days",
     *               "cost": 0,
     *               "is_active": 1,
     *               "created_at": "2021-05-29T07:34:51.000000Z",
     *               "updated_at": "2021-05-29T07:34:51.000000Z"
     *           },
     *           "gst_taxes": [
     *               {
     *                   "id": 8,
     *                   "package_id": 8,
     *                   "gst_id": 2,
     *                   "amount": 17.6,
     *                   "created_at": "2021-06-07T13:18:43.000000Z",
     *                   "updated_at": "2021-06-07T13:18:43.000000Z"
     *               }
     *           ],
     *           "seller": {
     *               "id": 4,
     *               "first_name": "Amazcart Ltd",
     *               "last_name": null,
     *               "username": "0156356563235",
     *               "photo": null,
     *               "role_id": 5, 
     *           },
     *           "delivery_process": {
     *               "id": 5,
     *               "name": "Delivered",
     *               "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,",
     *               "created_at": "2021-02-10T22:34:43.000000Z",
     *               "updated_at": "2021-02-10T22:35:02.000000Z"
     *           },
     *           "delivery_states": [
     *               {
     *                   "id": 2,
     *                   "order_package_id": 8,
     *                   "delivery_status": 5,
     *                   "note": null,
     *                   "date": "2021-06-08",
     *                   "created_by": 4,
     *                   "updated_by": null,
     *                   "created_at": "2021-06-08T08:51:16.000000Z",
     *                   "updated_at": "2021-06-08T08:51:16.000000Z"
     *               }
     *           ],
     *           "reviews": [
     *               {
     *                   "id": 1,
     *                   "customer_id": 5,
     *                   "seller_id": 4,
     *                   "product_id": 2,
     *                   "order_id": 5,
     *                   "package_id": 8,
     *                   "review": "test product review",
     *                   "rating": 4,
     *                   "is_anonymous": 1,
     *                   "status": 0,
     *                   "created_at": "2021-06-08T12:31:32.000000Z",
     *                   "updated_at": "2021-06-08T12:31:32.000000Z"
     *               }
     *           ]
     *       },
     *       "message": "success"
     * }
     */


    public function OrderReviewPackageWise(Request $request){
        $request->validate([
            'seller_id' => 'required',
            'package_id' => 'required',
            'order_id' => 'required',
        ]);
        $package = OrderPackageDetail::with('order','products','shipping','gst_taxes','seller','delivery_process','delivery_states','reviews','products.seller_product_sku.product.product','products.seller_product_sku.product_variations.attribute','products.seller_product_sku.product_variations.attribute_value.color')
            ->where('id', $request->package_id)->where('order_id', $request->order_id)->where('seller_id', $request->seller_id)->first();
        if($package){
            return response()->json([
                'package' => $package,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'not found'
            ],404);
        }
    }

    /**
     * Order Review Store
     * @bodyParam product_id integer required id of product
     * @bodyParam product_review string required product review with array format
     * @bodyParam seller_id integer required seller id
     * @bodyParam order_id integer required order id
     * @bodyParam package_id integer required package id
     * @bodyParam seller_rating double required seller rating
     * @bodyParam seller_review string required seller review
     * @bodyParam is_anonymous boolean nullable seller review
     * @bodyParam product_rating_{id} double required product rating
     * @bodyParam product_type string required product type with array product or gift_cart
     * 
     * @response 201{
     *      'message' : 'Review Done. Thanks for Review.'
     * }
     */

    public function OrderReview(Request $request){

        $request->validate([
            'product_id' =>'required',
            'product_review' => 'required',
            'seller_id' => 'required',
            'order_id' => 'required',
            'package_id' => 'required',
            'seller_rating' => 'required',
            'seller_review' => 'required',
            'product_type' => 'required'
        ]);


        DB::beginTransaction();
        try{
            $review = $this->productReviewService->store($request->except('_token'), $request->user());
            if($review){
                DB::commit();

                return response()->json([
                    'message' => 'Review Done. Thanks for Review.'
                ],201);
            }else{
                return response()->json([
                    'message' => 'Review already exsist'
                ],409);
            }

        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'something gone wrong'
            ],503);
        }

    }

    /**
     * Waiting for review list
     * @response{
     *      "packages": [
     *           {
     *               "id": 8,
     *               "order_id": 5,
     *               "seller_id": 4,
     *               "package_code": "TRK - 13620585",
     *               "number_of_product": 1,
     *               "shipping_cost": 10,
     *               "shipping_date": "deafultTheme.Est_arrival_date: 15 Jun - 19 Jun",
     *               "shipping_method": 3,
     *               "is_cancelled": 0,
     *               "is_reviewed": 0,
     *               "delivery_status": 5,
     *               "last_updated_by": 4,
     *               "tax_amount": 17.6,
     *               "created_at": "2021-06-07T13:18:43.000000Z",
     *               "updated_at": "2021-06-08T08:51:16.000000Z",
     *               "deliveryStateName": "Pending",
     *               "order": {
      *                  "id": 5,
      *                  "customer_id": 5,
      *                  "order_payment_id": null,
      *                  "order_type": null,
      *                  "order_number": "order-6726-210607071843",
      *                  "payment_type": 1,
      *                  "is_paid": 1,
      *                  "is_confirmed": 1,
      *                  "is_completed": 1,
      *                  "is_cancelled": 0,
      *                  "customer_email": "customer1@gmail.com",
      *                  "customer_phone": "016859865968",
      *                  "customer_shipping_address": 1,
      *                  "customer_billing_address": 3,
      *                  "number_of_package": 1,
      *                  "grand_total": 397.2,
      *                  "sub_total": 440,
      *                  "discount_total": 88,
      *                  "shipping_total": 10,
      *                  "number_of_item": 2,
      *                  "order_status": 5,
      *                  "tax_amount": 17.6,
      *                  "created_at": "2021-06-07T13:18:43.000000Z",
      *                  "updated_at": "2021-06-08T08:51:16.000000Z"
      *              }
     *           }
     *       ],
     *       "message": "success
     * }
     */

    public function waitingForReview(Request $request){
        
        $packages = $this->productReviewService->waitingForReview($request->user());
        if(count($packages) > 0){
            return response()->json([
                'packages' => $packages,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'package not found'
            ],404);
        }
    }

    /**
     * Review list
     * @response{
     *      "reviews": [
     *           {
     *               "id": 8,
     *               "order_id": 5,
     *               "seller_id": 4,
     *               "package_code": "TRK - 13620585",
     *               "number_of_product": 1,
     *               "shipping_cost": 10,
     *               "shipping_date": "deafultTheme.Est_arrival_date: 15 Jun - 19 Jun",
     *               "shipping_method": 3,
     *               "is_cancelled": 0,
     *               "is_reviewed": 1,
     *               "delivery_status": 5,
     *               "last_updated_by": 4,
     *               "tax_amount": 17.6,
     *               "created_at": "2021-06-07T13:18:43.000000Z",
     *               "updated_at": "2021-06-08T08:51:16.000000Z",
     *               "deliveryStateName": "Pending",
     *               "order": {
     *                   "id": 5,
     *                   "customer_id": 5,
     *                   "order_payment_id": null,
     *                   "order_type": null,
     *                   "order_number": "order-6726-210607071843",
     *                   "payment_type": 1,
     *                   "is_paid": 1,
     *                   "is_confirmed": 1,
     *                   "is_completed": 1,
     *                   "is_cancelled": 0,
     *                   "customer_email": "customer1@gmail.com",
     *                   "customer_phone": "016859865968",
     *                   "customer_shipping_address": 1,
     *                   "customer_billing_address": 3,
     *                   "number_of_package": 1,
     *                   "grand_total": 397.2,
     *                   "sub_total": 440,
     *                   "discount_total": 88,
     *                   "shipping_total": 10,
     *                   "number_of_item": 2,
     *                   "order_status": 5,
     *                   "tax_amount": 17.6,
     *                   "created_at": "2021-06-07T13:18:43.000000Z",
     *                   "updated_at": "2021-06-08T08:51:16.000000Z"
     *               },
     *               "reviews": [
     *                   {
     *                       "id": 1,
     *                       "customer_id": 5,
     *                       "seller_id": 4,
     *                       "product_id": 2,
     *                       "order_id": 5,
     *                       "package_id": 8,
     *                       "review": "test product review",
     *                       "rating": 4,
     *                       "is_anonymous": 1,
     *                       "status": 0,
     *                       "created_at": "2021-06-08T12:31:32.000000Z",
     *                       "updated_at": "2021-06-08T12:31:32.000000Z",
     *                       "giftcard": {
     *                               giftcard info....
     *                        },
     *                       "product": {},
     *                       "reply": null,
     *                       "seller": {
     *                           "id": 4,
     *                           "first_name": "Amazcart Ltd",
     *                           "last_name": null,
     *                           "username": "0156356563235",
     *                           "photo": null,
     *                           "role_id": 5,
     *                           "mobile_verified_at": null,
     *                           "email": "amazcart@gmail.com",
     *                           "is_verified": 1,
     *                           "verify_code": "74d68bde279426442de115eb532f9f51a21eb448",
     *                           "email_verified_at": null,
     *                           "notification_preference": "mail",
     *                           "is_active": 1,
     *                           "avatar": null,
     *                           "phone": null,
     *                           "date_of_birth": null,
     *                           "description": null,
     *                           "secret_login": 0,
     *                           "secret_logged_in_by_user": null,
     *                           "created_at": "2021-05-29T07:15:56.000000Z",
     *                           "updated_at": "2021-05-29T07:15:56.000000Z"
     *                       },
     *                       "images": []
     *                   }
     *               ],
     *               "gift_card_reviews": []
     *           }
     *       ],
     *       "message": "success"
     * }
     */

    public function reviewList(Request $request){
        $reviews = $this->productReviewService->reviewList($request->user()->id);

        if(count($reviews) > 0){
            return response()->json([
                'reviews' => $reviews,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'package not found'
            ],404);
        }
    }

    /**
     * Make Refund Page data
     * @urlParam id integer required id of order
     * @response{
     *  "order": {
     *       "id": 5,
     *       "customer_id": 5,
     *       "order_payment_id": null,
     *       "order_type": null,
     *       "order_number": "order-6726-210607071843",
     *       "payment_type": 1,
     *       "is_paid": 1,
     *       "is_confirmed": 1,
     *       "is_completed": 1,
     *       "is_cancelled": 0,
     *       "customer_email": "customer1@gmail.com",
     *       "customer_phone": "016859865968",
     *       "customer_shipping_address": 1,
     *       "customer_billing_address": 3,
     *       "number_of_package": 1,
     *       "grand_total": 397.2,
     *       "sub_total": 440,
     *       "discount_total": 88,
     *       "shipping_total": 10,
     *       "number_of_item": 2,
     *       "order_status": 5,
     *       "tax_amount": 17.6,
     *       "created_at": "2021-06-07T13:18:43.000000Z",
     *       "updated_at": "2021-06-08T08:51:16.000000Z",
     *       "customer": {
     *           customer info ....
     *       },
     *       "packages": [
     *           packages ....
     *       ]
     *   },
     *   "shipping_methods": [
     *       shipping methods ...
     *   ],
     *   "reasons": [
     *       {
     *           "id": 3,
     *           "reason": "Broken or tear.",
     *           "created_at": "2021-06-08T05:16:00.000000Z",
     *           "updated_at": "2021-06-08T05:24:02.000000Z"
     *       }
     *   ]
     * }
     */

    public function makeRefundData(Request $request, $id){

        $orderRepo = new OrderRepository;
        $refundReasonRepo = new RefundReasonRepository;
        $shippingService = new ShippingRepository;
        $data['order'] = $orderRepo->orderFindByID($id);
        $data['shipping_methods'] = $shippingService->getActiveAll();
        $data['reasons'] = $refundReasonRepo->getAll();

        if($data['order']){
            return response()->json($data, 200);
        }else{
            return response()->json([
                'message' => 'order not found'
            ],404);
        }
        
    }

    /**
     * Refund Store
     * @bodyParam order_id integer required order id example : 5
     * @bodyParam product_ids string required product info-> package_id-product_id-seller_id-amount example : 3-5-7-116.09
     * @bodyParam money_get_method string required wallet or bank_transfer example : wallet
     * @bodyParam shipping_way string required courier or drop_off example : courier
     * @bodyParam qty_{package_id} string required quantity for which product example : 1
     * @bodyParam reason_{package_id} string required reason for which product example : 1
     * @bodyParam additional_info string nullable  additional information
     * @bodyParam bank_name string nullable  bank name
     * @bodyParam branch_name string nullable  branch name
     * @bodyParam account_name string nullable  account name
     * @bodyParam account_no string nullable  account no
     * @bodyParam couriers integer nullable  shipping method id example: 1
     * @bodyParam pick_up_address_id integer nullable  customer address id example: 1
     * @bodyParam drop_off_couriers integer nullable  shipping method id  id example: 1
     * @bodyParam drop_off_courier_address string nullable  drop off courier address
     * 
     * @response 201{
     *      'message' : 'refund successfully'
     * }
     */

    public function refundStore(Request $request){

        $request->validate([
            'order_id' => 'required',
            'product_ids' => 'required',
            'money_get_method' => 'required',
            'shipping_way' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $refund =  $this->refundService->store($request->except("_token"), $request->user());
            DB::commit();
            if($refund){
                return response()->json([
                    'message' => 'refund successfully'
                ],201);
            }else{
                return response()->json([
                    'message' => 'refund not complete. something gone wrong'
                ],500);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'something gone wrong'
            ],500);

        }
    }


    /**
     * Payment info store
     * @bodyParam amount double required total amount example : 500
     * @bodyParam transection_id string required payment transection id example: hdufyu4785793489834
     * @bodyParam payment_method integer required payment method id example : 4
     * 
     * 
     * @response 201{
     *      "payment_info": {
     *       "user_id": 8,
     *       "amount": "500",
     *       "payment_method": "4",
     *       "txn_id": "siu38475wefksdfiduir",
     *       "updated_at": "2021-07-06T10:20:52.000000Z",
     *       "created_at": "2021-07-06T10:20:52.000000Z",
     *       "id": 2
     *   },
     *   "message": "payment successfull"
     * }
     */

    public function paymentInfoStore(Request $request){
        $request->validate([
            'amount' => 'required',
            'transection_id' => 'required',
            'payment_method' => 'required'
        ]);

        $order_repo = new OrderRepository;
        $payment = $order_repo->orderPaymentDone($request->amount,$request->payment_method , $request->transection_id, $request->user());

        return response()->json([
            'payment_info' => $payment,
            'message' => 'payment successfull'
        ], 201);

    }



}
