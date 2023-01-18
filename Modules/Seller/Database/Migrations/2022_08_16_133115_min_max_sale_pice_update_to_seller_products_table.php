<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Seller\Entities\SellerProduct;

class MinMaxSalePiceUpdateToSellerProductsTable extends Migration
{
    
    public function up()
    {
        if(Schema::hasTable('seller_products')){
            $list1 = SellerProduct::with('skus')->whereBetween('id', [1, 500])->get();
            if($list1->count()){
                foreach($list1 as $product){
                    $min = $product->skus->min('selling_price');
                    $max = $product->skus->max('selling_price');
                    $product->update([
                        'min_sell_price' => $min,
                        'max_sell_price' => $max
                    ]);
                }
            }

            $list2 = SellerProduct::with('skus')->whereBetween('id', [501, 1000])->get();
            if($list2->count()){
                foreach($list2 as $product){
                    $min = $product->skus->min('selling_price');
                    $max = $product->skus->max('selling_price');
                    $product->update([
                        'min_sell_price' => $min,
                        'max_sell_price' => $max
                    ]);
                }
            }
            $list3 = SellerProduct::with('skus')->whereBetween('id', [1001, 1500])->get();
            if($list3){
                foreach($list3 as $product){
                    $min = $product->skus->min('selling_price');
                    $max = $product->skus->max('selling_price');
                    $product->update([
                        'min_sell_price' => $min,
                        'max_sell_price' => $max
                    ]);
                }
            }
            $list4 = SellerProduct::with('skus')->where('id', '>',1500)->get();
            if($list4){
                foreach($list4 as $product){
                    $min = $product->skus->min('selling_price');
                    $max = $product->skus->max('selling_price');
                    $product->update([
                        'min_sell_price' => $min,
                        'max_sell_price' => $max
                    ]);
                }
            }
        }
    }

    
    public function down()
    {
        //
    }
}
