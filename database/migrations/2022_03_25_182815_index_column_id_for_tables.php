<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IndexColumnIdForTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_product', function (Blueprint $table) {
            $table->index(['category_id', 'product_id']);
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->index(['id','parent_id','status']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->index(['id','brand_id','status']);
        });
        Schema::table('seller_products', function (Blueprint $table) {
            $table->index(['id', 'product_id','status']);
        });
        Schema::table('brands', function (Blueprint $table) {
            $table->index(['id','status']);
        });
        Schema::table('seller_product_s_k_us', function (Blueprint $table) {
            $table->index(['id','user_id', 'product_id','product_sku_id']);
        });
        Schema::table('product_sku', function (Blueprint $table) {
            $table->index(['id','status', 'selling_price']);
        });



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
