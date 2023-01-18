<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MinimumShoppingAmountAddToShippingMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('shipping_methods') && !Schema::hasColumn('shipping_methods', 'minimum_shopping')){
            Schema::table('shipping_methods', function (Blueprint $table) {
                $table->double('minimum_shopping')->default(0)->after('cost');
            });   
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('shipping_methods') && Schema::hasColumn('shipping_methods', 'minimum_shopping')){
            Schema::table('shipping_methods', function (Blueprint $table) {
                $table->dropColumn('minimum_shopping');
            });
        }
    }
}
