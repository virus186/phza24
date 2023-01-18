<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormBuilderPermissionTable extends Migration
{

    public function up()
    {
        $permission = [
            ['id' => 667, 'module_id' => 42, 'parent_id' => null, 'module' => 'FormBuilder', 'name' => 'Form Builder', 'route' => 'form_builder', 'type' => 1],
            ['id' => 668, 'module_id' => 42, 'parent_id' => 667, 'module' => 'FormBuilder', 'name' => 'Forms', 'route' => 'form_builder.forms.index', 'type' => 2],
            ['id' => 669, 'module_id' => 42, 'parent_id' => 668, 'module' => 'FormBuilder', 'name' => 'List', 'route' => 'form_builder.forms.index', 'type' => 3],
            ['id' => 670, 'module_id' => 42, 'parent_id' => 668, 'module' => 'FormBuilder', 'name' => 'Form Builder', 'route' => 'form_builder.builder', 'type' => 3],
            ['id' => 671, 'module_id' => 42, 'parent_id' => 668, 'module' => 'FormBuilder', 'name' => 'View', 'route' => 'form_builder.forms.show', 'type' => 3],
        ];

        try{
            DB::table('permissions')->insert($permission);
        }catch(Exception $e){

        }

    }


    public function down()
    {
        Schema::dropIfExists('form_builder_permission');
    }
}
