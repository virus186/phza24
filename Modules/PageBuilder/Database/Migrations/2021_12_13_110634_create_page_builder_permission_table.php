<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageBuilderPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
            |--------------------------------------------------------------------------
            |Page Builder Module Permission
            |--------------------------------------------------------------------------
       */
        $permission = [
            ['id' => 660, 'module_id' => 41, 'parent_id' => null, 'module' => 'PageBuilder', 'name' => 'Page Builder', 'route' => 'page_builder', 'type' => 1],
            ['id' => 661, 'module_id' => 41, 'parent_id' => 660, 'module' => 'PageBuilder', 'name' => 'Custom Page', 'route' => 'page_builder.pages.index', 'type' => 2],
            ['id' => 662, 'module_id' => 41, 'parent_id' => 661, 'module' => 'PageBuilder', 'name' => 'List', 'route' => 'page_builder.pages.index', 'type' => 3],
            ['id' => 663, 'module_id' => 41, 'parent_id' => 661, 'module' => 'PageBuilder', 'name' => 'View', 'route' => 'page_builder.pages.show', 'type' => 3],
            ['id' => 664, 'module_id' => 41, 'parent_id' => 661, 'module' => 'PageBuilder', 'name' => 'Update', 'route' => 'page_builder.pages.update', 'type' => 3],
            ['id' => 665, 'module_id' => 41, 'parent_id' => 661, 'module' => 'PageBuilder', 'name' => 'Delete', 'route' => 'page_builder.pages.destroy', 'type' => 3],
            ['id' => 666, 'module_id' => 41, 'parent_id' => 661, 'module' => 'PageBuilder', 'name' => 'Design', 'route' => 'page_builder.pages.design.update', 'type' => 3],

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

    }
}
