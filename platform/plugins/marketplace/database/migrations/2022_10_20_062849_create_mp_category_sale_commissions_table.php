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
        Schema::dropIfExists('mp_category_sale_commissions');

        Schema::create('mp_category_sale_commissions', function (Blueprint $table) {
            $table->id();
            $table->integer('product_category_id')->unsigned()->unique();
            $table->decimal('commission_percentage')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_category_sale_commissions');
    }
};
