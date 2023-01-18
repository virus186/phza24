<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;
use Modules\SidebarManager\Entities\Backendmenu;
use Modules\SidebarManager\Entities\BackendmenuUser;

class AddPermissionForSocialLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = [
            ['id'  => 735, 'module_id' => 3, 'parent_id' => 26, 'name' => 'Social Link', 'route' => 'frontendcms.socialLink', 'type' => 2 ],

        ];

        try{
            DB::table('permissions')->insert($permission);
        }catch(Exception $e){

        }
        if(Schema::hasTable('backendmenus')){
            $sql = [
                ['id' => 227, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'Social Link', 'route' => 'frontendcms.socialLink', 'position' => 13],//Submenu
            ];

            foreach($sql as $menu){
                $children = null;
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
        }
        if(Schema::hasTable('general_settings')){
            $data = DB::table('general_settings')->select(['facebook','twitter','linkedin','instagram'])->first();
            if(@$data->facebook){
                DB::table('seller_social_links')->insert([
                    'user_id' => 1,
                    'url' => $data->facebook,
                    'icon' => 'fab fa-facebook',
                    'status' => 1
                ]);
            }
            if(@$data->twitter){
                DB::table('seller_social_links')->insert([
                    'user_id' => 1,
                    'url' => $data->twitter,
                    'icon' => 'fab fa-twitter',
                    'status' => 1
                ]);
            }
            if(@$data->linkedin){
                DB::table('seller_social_links')->insert([
                    'user_id' => 1,
                    'url' => $data->linkedin,
                    'icon' => 'fab fa-linkedin',
                    'status' => 1
                ]);
            }
            if(@$data->instagram){
                DB::table('seller_social_links')->insert([
                    'user_id' => 1,
                    'url' => $data->instagram,
                    'icon' => 'fab fa-instagram',
                    'status' => 1
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
        Permission::destroy([735]);
        if(Schema::hasTable('backendmenus')){
            $backend_menus = Backendmenu::where('module', 'FrontendCMS')->pluck('id')->toArray();
            $backend_menu_users = BackendmenuUser::whereIn('backendmenu_id', $backend_menus)->pluck('id')->toArray();
            Backendmenu::destroy($backend_menus);
            BackendmenuUser::destroy($backend_menu_users);
        }
    }
}
