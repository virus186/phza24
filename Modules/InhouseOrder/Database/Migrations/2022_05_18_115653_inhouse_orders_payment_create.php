<?php

use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InhouseOrdersPaymentCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('orders')){
            $inhouse_orders = Order::where('order_type', 'inhouse_order')->get();
            $orderRepo = new OrderRepository();
            foreach($inhouse_orders as $order){
                if(!$order->order_payment){
                    $order_payment = $orderRepo->orderPaymentDone($order->grand_total, 1, "none", null);
                    $order_payment->update([
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                    ]);
                    $order->update([
                        'order_payment_id' => $order_payment->id
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
