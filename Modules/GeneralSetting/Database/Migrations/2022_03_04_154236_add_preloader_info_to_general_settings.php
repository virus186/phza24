<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;
use Modules\SidebarManager\Entities\Sidebar;

class AddPreloaderInfoToGeneralSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('general_settings')){
            Schema::table('general_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('preloader_type')->default(1)->nullable()->after('meta_description');
                $table->boolean('preloader_status')->default(1)->after('preloader_type');
                $table->unsignedInteger('preloader_style')->default(0)->after('preloader_status');
                $table->string('preloader_image')->nullable()->after('preloader_style');
            });
        }

        if(Schema::hasTable('permissions')){
            $sql = [
                //configuration
                ['id' => 703, 'module_id' => 4, 'parent_id' => 68, 'name' => 'Preloader Setting', 'route' => 'appearance.pre-loader', 'type' => 2 ],
                ['id' => 704, 'module_id' => 4, 'parent_id' => 703, 'name' => 'Update', 'route' => 'appearance.pre-loader.update', 'type' => 3 ]
            ];
            try{
                DB::table('permissions')->insert($sql);
            }catch(Exception $e){

            }
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
            $table->dropColumn('preloader_type');
            $table->dropColumn('preloader_status');
            $table->dropColumn('preloader_style');
            $table->dropColumn('preloader_image');
        });

        Permission::destroy([701,702]);
    }
}
