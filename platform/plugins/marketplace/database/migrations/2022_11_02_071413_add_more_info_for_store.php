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
        Schema::table('mp_vendor_info', function (Blueprint $table) {
            $table->string('payout_payment_method', 120)->nullable()->default('bank_transfer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_vendor_info', function (Blueprint $table) {
            $table->dropColumn('payout_payment_method');
        });
    }
};
