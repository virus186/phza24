<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;

class AddPermissionSidebarForConfigurationMarketingModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = [
            ['id'  => 636, 'module_id' => 10, 'parent_id' => 122, 'name' => 'Configuration', 'route' => 'marketing.configuration', 'type' => 2 ]
        ];
        if(Schema::hasTable('permissions')){
            DB::table('permissions')->insert($sql);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::destroy([636]);
    }
}
