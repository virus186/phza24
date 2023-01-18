<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;

class RemoveVisitorPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = [
            ['id' => 734, 'module_id' => 38, 'parent_id' => 631, 'name' => 'Remove Visitor', 'route' => 'utilities.remove_visitor', 'type' => 2],
        ];

        try{
            DB::table('permissions')->insert($permission);
        }catch(Exception $e){

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { 
        Permission::destroy([734]);
    }
}
