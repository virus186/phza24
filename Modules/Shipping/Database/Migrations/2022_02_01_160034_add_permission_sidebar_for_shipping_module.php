<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;

class AddPermissionSidebarForShippingModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('permissions')){
            Permission::destroy([363, 364, 365, 366, 367, 368]);
            $sql = [
                ['id' => 363, 'module_id' => 44, 'parent_id' => null, 'name' => 'Shipping', 'route' => 'shipping_methods', 'type' => 1 ],
                ['id' => 364, 'module_id' => 44, 'parent_id' => 363, 'name' => 'Shipping Rate', 'route' => 'shipping_methods.index', 'type' => 2 ],
                ['id' => 365, 'module_id' => 44, 'parent_id' => 364, 'name' => 'Create', 'route' => 'shipping_methods.store', 'type' => 3 ],
                ['id' => 366, 'module_id' => 44, 'parent_id' => 364, 'name' => 'Update', 'route' => 'shipping_methods.update', 'type' => 3 ],
                ['id' => 367, 'module_id' => 44, 'parent_id' => 364, 'name' => 'Delete', 'route' => 'shipping_methods.destroy', 'type' => 3 ],
                ['id' => 368, 'module_id' => 44, 'parent_id' => 364, 'name' => 'Status', 'route' => 'shipping_methods.update_status', 'type' => 3 ],
                ['id' => 678, 'module_id' => 44, 'parent_id' => 364, 'name' => 'Approve', 'route' => 'shipping_methods.update_approve_status', 'type' => 3 ],

                // Carriers
                ['id' => 679, 'module_id' => 44, 'parent_id' => 363, 'name' => 'Carriers', 'route' => 'shipping.carriers.index', 'type' => 2 ],
                ['id' => 680, 'module_id' => 44, 'parent_id' => 679, 'name' => 'Status', 'route' => 'shipping.carriers.status', 'type' => 3 ],
                ['id' => 692, 'module_id' => 44, 'parent_id' => 679, 'name' => 'Update', 'route' => 'shipping.carrier.update', 'type' => 3 ],
                ['id' => 693, 'module_id' => 44, 'parent_id' => 679, 'name' => 'Delete', 'route' => 'shipping.carrier.destroy', 'type' => 3 ],

                // pickup locations
                ['id' => 681, 'module_id' => 44, 'parent_id' => 363, 'name' => 'Pickup Locations', 'route' => 'shipping.pickup_locations.index', 'type' => 2 ],
                ['id' => 682, 'module_id' => 44, 'parent_id' => 681, 'name' => 'Create', 'route' => 'shipping.pickup_locations.store', 'type' => 3 ],
                ['id' => 683, 'module_id' => 44, 'parent_id' => 681, 'name' => 'Update', 'route' => 'shipping.pickup_locations.update', 'type' => 3 ],
                ['id' => 684, 'module_id' => 44, 'parent_id' => 681, 'name' => 'Delete', 'route' => 'shipping.pickup_locations.destroy', 'type' => 3 ],
                ['id' => 685, 'module_id' => 44, 'parent_id' => 681, 'name' => 'Status', 'route' => 'shipping.pickup_locations.status', 'type' => 3 ],
                ['id' => 686, 'module_id' => 44, 'parent_id' => 681, 'name' => 'Set Default Pickup Location', 'route' => 'shipping.pickup_locations.set', 'type' => 3 ],

                // shipping orders
                ['id' => 687, 'module_id' => 44, 'parent_id' => 363, 'name' => 'Shipping Orders', 'route' => 'shipping.pending_orders.index', 'type' => 2 ],
                ['id' => 688, 'module_id' => 44, 'parent_id' => 687, 'name' => 'Download Label', 'route' => 'shipping.label_generate', 'type' => 3 ],
                ['id' => 689, 'module_id' => 44, 'parent_id' => 687, 'name' => 'Update Shipping Methods', 'route' => 'shipping.method_update', 'type' => 3 ],
                ['id' => 694, 'module_id' => 44, 'parent_id' => 687, 'name' => 'Carriers Order Update', 'route' => 'shipping.carrier_order_update', 'type' => 3 ],

                //configuration
                ['id' => 690, 'module_id' => 44, 'parent_id' => 363, 'name' => 'Configuration', 'route' => 'shipping.configuration.index', 'type' => 2 ],
                ['id' => 691, 'module_id' => 44, 'parent_id' => 690, 'name' => 'Update', 'route' => 'shipping.configuration.update', 'type' => 3 ],
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
