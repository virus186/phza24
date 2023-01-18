<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;

class ChangeColorSchemePermisionRouteName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('permissions')){
            $color_scheme_index = Permission::where('id', 561)->where('route', 'themeColor.index')->first();
            if($color_scheme_index){
                $color_scheme_index->update([
                    'route' => 'appearance.themeColor.index'
                ]);
            }
            $color_scheme_update = Permission::where('id', 562)->where('route', 'themeColor.update')->first();
            if($color_scheme_update){
                $color_scheme_update->update([
                    'route' => 'appearance.themeColor.update'
                ]);
            }
            $color_scheme_active = Permission::where('id', 563)->where('route', 'themeColor.activate')->first();
            if($color_scheme_active){
                $color_scheme_active->update([
                    'route' => 'appearance.themeColor.activate'
                ]);
            }
        }


        if(Schema::hasTable('theme_colors')){
            DB::statement("ALTER TABLE `theme_colors` CHANGE `status` `status` TINYINT NOT NULL DEFAULT '0';");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
