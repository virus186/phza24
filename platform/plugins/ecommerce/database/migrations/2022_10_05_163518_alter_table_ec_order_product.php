<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('ec_order_product', 'product_options')) {
            Schema::table('ec_order_product', function (Blueprint $table) {
                $table->text('product_options')->after('options')->nullable()->comment('product option data');
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
        Schema::table('ec_order_product', function (Blueprint $table) {
            $table->dropColumn('product_options');
        });
    }
};
