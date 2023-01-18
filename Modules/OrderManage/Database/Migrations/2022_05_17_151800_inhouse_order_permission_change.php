<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;

class InhouseOrderPermissionChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('permissions')){
            $inhouse_permissions = Permission::whereIn('id', [297,305,306,307,308,309,310,311])->get();
            if($inhouse_permissions->count() > 0){
                foreach($inhouse_permissions as $inhouse_permission){
                    $inhouse_permission->update([
                        'module' => null
                    ]);
                }
            }else{
                $sql = [
                    ['id' => 297, 'module_id' => 16, 'parent_id' => 291, 'name' => 'Inhouse', 'route' => 'inhouse_orders', 'type' => 3 ],
                    ['id' => 305, 'module_id' => 16, 'parent_id' => 290, 'name' => 'InHouse Order', 'route' => 'admin.inhouse-order.get-data', 'type' => 2 ],
                    ['id' => 306, 'module_id' => 16, 'parent_id' => 305, 'name' => 'Confirmed', 'route' => 'inhouse_order_confirmed', 'type' => 3 ],
                    ['id' => 307, 'module_id' => 16, 'parent_id' => 305, 'name' => 'Completed', 'route' => 'inhouse_order_completed', 'type' => 3 ],
                    ['id' => 308, 'module_id' => 16, 'parent_id' => 305, 'name' => 'Pending', 'route' => 'inhouse_order_pending', 'type' => 3 ],
                    ['id' => 309, 'module_id' => 16, 'parent_id' => 305, 'name' => 'Cancelled', 'route' => 'inhouse_order_cancelled', 'type' => 3 ],
                    ['id' => 310, 'module_id' => 16, 'parent_id' => 305, 'name' => 'Create', 'route' => 'admin.inhouse-order.create', 'type' => 3 ],
                    ['id' => 311, 'module_id' => 16, 'parent_id' => 305, 'name' => 'Delete', 'route' => 'admin.inhouse-order.delete', 'type' => 3 ]
                ];
                DB::table('permissions')->insert($sql);
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
        //
    }
}
