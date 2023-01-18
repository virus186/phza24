<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Entities\CustomerAddress;

class CreateOrderAddressDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_address_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_country_id')->nullable();
            $table->string('shipping_state_id')->nullable();
            $table->string('shipping_city_id')->nullable();
            $table->string('shipping_postcode')->nullable();
            $table->boolean('bill_to_same_address')->default(1);
            $table->string('billing_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_country_id')->nullable();
            $table->string('billing_state_id')->nullable();
            $table->string('billing_city_id')->nullable();
            $table->string('billing_postcode')->nullable();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });

        $orders = Order::where('customer_id', '!=', null)->get();
        $sql = [];
        foreach($orders as $order){
            $shipping_address = CustomerAddress::find($order->customer_shipping_address);
            $is_same_billing = 1;
            if($order->customer_shipping_address != $order->customer_billing_address){
                $billing_address = CustomerAddress::find($order->customer_billing_address);
                $is_same_billing = 0;
            }else{
                $billing_address = $shipping_address;
            }
            $sql[] = [
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'shipping_name' => @$shipping_address->name,
                'shipping_email' => @$shipping_address->email,
                'shipping_phone' => @$shipping_address->phone,
                'shipping_address' => @$shipping_address->address,
                'shipping_country_id' => @$shipping_address->country,
                'shipping_state_id' => @$shipping_address->state,
                'shipping_city_id' => @$shipping_address->city,
                'shipping_postcode' => @$shipping_address->postal_code,
                'bill_to_same_address' => @$is_same_billing,
                'billing_name' => @$billing_address->name,
                'billing_email' => @$billing_address->email,
                'billing_phone' => @$billing_address->phone,
                'billing_address' => @$billing_address->address,
                'billing_country_id' => @$billing_address->country,
                'billing_state_id' => @$billing_address->state,
                'billing_city_id' => @$billing_address->city,
                'billing_postcode' => @$billing_address->postal_code,
            ];
        }
        DB::table('order_address_details')->insert($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_address_details');
    }
}
