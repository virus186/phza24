<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;
use Modules\SidebarManager\Entities\Backendmenu;

class AddCustomAssetToMenuAndPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('permissions')){
            $sql = [
                ['id' => 726, 'module_id' => 4, 'parent_id' => 68, 'name' => 'Custom asset', 'route' => 'appearance.custom-asset', 'type' => 2],
                ['id' => 727, 'module_id' => 4, 'parent_id' => 726, 'name' => 'Custom asset', 'route' => 'appearance.custom-asset-store', 'type' => 3]
            ];
            DB::table('permissions')->insert($sql);
        }

        if(Schema::hasTable('backendmenus')){
            $menu_sql = [
                ['is_admin' => 1,'is_seller' => 0, 'icon' =>'fa fa-product-hunt', 'name' => 'appearance.Custom asset','parent_id' => 28, 'route' => 'appearance.custom-asset', 'position' => 7]//Submenu
            ];

            foreach($menu_sql as $menu){
                $children = null;
                $parent = null;
                if(array_key_exists('children',$menu)){
                    $children = $menu['children'];
                    unset( $menu['children']);
                }
                $parent = Backendmenu::create($menu);
                if($children){
                    foreach($children as $menu){
                        $sub_children = null;
                        if(array_key_exists('children',$menu)){
                            $sub_children = $menu['children'];
                            unset( $menu['children']);
                        }
                        $menu['parent_id'] = $parent->id;
                        $parent_children = Backendmenu::create($menu);
                        if($sub_children){
                            foreach($sub_children as $menu){
                                $subsubmenu['parent_id'] = $parent_children->id;
                                Backendmenu::create($subsubmenu);
                            }
                        }
                    }
                }
            }

            $sms_template = Backendmenu::where('route', 'sms_templates.index')->first();
            if($sms_template){
                $sms_template->update([
                    'name' => 'general_settings.sms_template'
                ]);
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
        Permission::destroy([726, 727]);
    }
}
