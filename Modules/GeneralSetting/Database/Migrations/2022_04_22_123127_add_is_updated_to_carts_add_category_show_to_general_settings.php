<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsUpdatedToCartsAddCategoryShowToGeneralSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('carts') && !Schema::hasColumn('carts','is_updated')){
            Schema::table('carts', function (Blueprint $table) {
                $table->boolean('is_updated')->default(0)->after('shipping_method_id');
            });
        }
        if(Schema::hasTable('general_settings')){
            Schema::table('general_settings', function (Blueprint $table) {
                $table->string('category_show_in_frontend',10)->default('all')->after('guest_checkout');
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
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('category_show_in_frontend');
        });
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('is_updated');
        });
    }
}
