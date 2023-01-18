<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;

class AddNewPermissionToShippingModule extends Migration
{
    public function up()
    {
        if(Schema::hasTable('permissions')){

            $sql = [
                //configuration
                ['id' => 695, 'module_id' => 44, 'parent_id' => 363, 'name' => 'Label Configuration', 'route' => 'shipping.label.terms_condition.index', 'type' => 2 ],
                ['id' => 696, 'module_id' => 44, 'parent_id' => 687, 'name' => 'Invoice', 'route' => 'shipping.invoice_generate', 'type' => 3 ],
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
        $ids = Permission::where('module_id', 44)->pluck('id')->toArray();
        Permission::destroy($ids);
    }
}
