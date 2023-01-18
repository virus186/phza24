<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingAmountMultiplyWithQtyToShippingConfigurationsTable extends Migration
{
    
    public function up()
    {
        Schema::table('shipping_configurations', function (Blueprint $table) {
            $table->boolean('amount_multiply_with_qty')->default(0)->after('seller_use_shiproket');
        });
    }

   
    public function down()
    {
        Schema::table('shipping_configurations', function (Blueprint $table) {
            $table->dropColumn('amount_multiply_with_qty');
        });
    }
}
