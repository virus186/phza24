<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;

class FileStoragePermissionSidebarToRoot extends Migration
{
    
    public function up()
    {
        if(Schema::hasTable('permissions')){
            $is_exists = Permission::whereIn('id', [701, 702])->pluck('id')->toArray();
            if(count($is_exists) < 1){
                $sql = [
                    //configuration
                    ['id' => 701, 'module_id' => 46, 'parent_id' => null, 'name' => 'File Storage', 'route' => 'file-storage.index', 'type' => 1 ],
                    ['id' => 702, 'module_id' => 46, 'parent_id' => 701, 'name' => 'Update', 'route' => 'DefaultStorageSettingSubmit', 'type' => 2 ]
                ];
                try{
                    DB::table('permissions')->insert($sql);
                }catch(Exception $e){
    
                }
            }
        }
        if(Schema::hasTable('business_settings')){
            $row = \Modules\GeneralSetting\Entities\BusinessSetting::where('category_type','file_storage')->where('type','Local')->first();
            if(!$row){
                \Modules\GeneralSetting\Entities\BusinessSetting::create(
                    [
                        'category_type' => 'file_storage',
                        'type' => 'Local',
                        'status' => '1',
                    ]
                );
            }
        }
    }

    
    public function down()
    {
        $ids = Permission::whereIn('id', [701, 702])->pluck('id')->toArray();
        if(count($ids) > 0){
            Permission::destroy($ids);
        }
    }
}
