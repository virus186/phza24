<?php

use App\Models\OrderProductDetail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TaxAddOnOrderProductDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('order_product_details')){
            $order_products = OrderProductDetail::where('type', 'product')->where('tax_amount', 0)->get();
            $recs = new \Illuminate\Database\Eloquent\Collection($order_products);
            $order_products = $recs->groupBy('package_id');
            foreach($order_products as $order_items){
                $count_items = count($order_items);
                $tax_amount = $order_items[0]->package->tax_amount;
                if($tax_amount > 0){
                    $this_item_tax = $tax_amount/$count_items;
                    $this_item_tax = number_format($this_item_tax,2);
                }else{
                    $this_item_tax = 0;
                }
                foreach($order_items as $product){
                    $product->update([
                        'tax_amount' => $this_item_tax
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
