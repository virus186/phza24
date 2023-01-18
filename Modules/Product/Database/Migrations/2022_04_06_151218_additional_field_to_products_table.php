<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditionalFieldToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('products')){
            Schema::table('products', function (Blueprint $table) {
                $table->string('subtitle_1')->nullable()->after('stock_manage');
                $table->string('subtitle_2')->nullable()->after('subtitle_1');
            });
        }
        if(Schema::hasTable('seller_products')){
            Schema::table('seller_products', function (Blueprint $table) {
                $table->string('subtitle_1')->nullable()->after('recent_view');
                $table->string('subtitle_2')->nullable()->after('subtitle_1');
            });
        }
        if(Schema::hasTable('general_settings')){
            Schema::table('general_settings', function (Blueprint $table) {
                $table->boolean('product_subtitle_show')->default(0)->after('preloader_image');
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
        if(Schema::hasTable('products')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('subtitle_1');
                $table->dropColumn('subtitle_2');
            });
        }
        if(Schema::hasTable('seller_products')){
            Schema::table('seller_products', function (Blueprint $table) {
                $table->dropColumn('subtitle_1');
                $table->dropColumn('subtitle_2');
            });
        }
    }
}
