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
        Schema::dropIfExists('ec_options');
        Schema::dropIfExists('ec_global_options');

        Schema::create('ec_options', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of options');
            $table->string('option_type')->nullable()->comment('option type');
            $table->bigInteger('product_id')->default(0);
            $table->integer('order')->default(9999);
            $table->boolean('required')->default(false)->comment('Checked if this option is required');
            $table->timestamps();
        });

        Schema::create('ec_global_options', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of options');
            $table->string('option_type')->comment('option type');
            $table->boolean('required')->default(false)->comment('Checked if this option is required');
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
        Schema::dropIfExists('ec_options');
        Schema::dropIfExists('ec_global_options');
    }
};
