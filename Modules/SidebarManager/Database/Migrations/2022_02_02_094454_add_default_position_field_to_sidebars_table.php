<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Modules\ModuleManager\Entities\InfixModuleManager;
class AddDefaultPositionFieldToSidebarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(Schema::hasTable('infix_module_managers')){
            $modules = InfixModuleManager::whereIn('name', ['FormBuilder', 'PageBuilder'])->get();
            foreach($modules as $module){
                $module->purchase_code = \Str::uuid();
                $module->checksum = \Str::uuid();
                $module->save();
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

    }
}
