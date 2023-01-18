<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShowTitleToNewUserZone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(Schema::hasTable('new_user_zones') && !Schema::hasColumn('new_user_zones', 'title_show')){
            Schema::table('new_user_zones', function (Blueprint $table) {
                $table->boolean('title_show')->default(1)->after('is_featured');
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
        
    }
}
