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
        Schema::dropIfExists('ec_option_value');
        Schema::dropIfExists('ec_global_option_value');

        Schema::create('ec_option_value', function (Blueprint $table) {
            $table->bigInteger('option_id')->comment('option id');
            $table->tinyText('option_value')->nullable()->comment('option value');
            $table->double('affect_price')->nullable()->comment('value of price of this option affect');
            $table->integer('order')->default(9999);
            $table->tinyInteger('affect_type')->default(0)->comment('0. fixed 1. percent');
            $table->timestamps();
        });

        Schema::create('ec_global_option_value', function (Blueprint $table) {
            $table->bigInteger('option_id')->comment('option id');
            $table->tinyText('option_value')->nullable()->comment('option value');
            $table->double('affect_price')->nullable()->comment('value of price of this option affect');
            $table->integer('order')->default(9999);
            $table->tinyInteger('affect_type')->default(0)->comment('0. fixed 1. percent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_option_value');
        Schema::dropIfExists('ec_global_option_value');
    }
};
