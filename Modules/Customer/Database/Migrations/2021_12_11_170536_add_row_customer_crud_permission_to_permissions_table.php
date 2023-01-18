<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;

class AddRowCustomerCrudPermissionToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission_sql = [
            ['id'  => 645, 'module_id' => 5, 'parent_id' => 82, 'name' => 'Create', 'route' => 'admin.customer.create', 'type' => 2 ],
            ['id'  => 646, 'module_id' => 5, 'parent_id' => 82, 'name' => 'Update', 'route' => 'admin.customer.edit', 'type' => 2 ],
            ['id'  => 647, 'module_id' => 5, 'parent_id' => 82, 'name' => 'Delete', 'route' => 'admin.customer.destroy', 'type' => 2 ],
            ['id'  => 734, 'module_id' => 5, 'parent_id' => 82, 'name' => 'Upload', 'route' => 'admin.customer.bulk_upload', 'type' => 2 ],
        ];

        try{
            DB::table('permissions')->insert($permission_sql);
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
        Permission::destroy([645,646,647]);
    }
}
