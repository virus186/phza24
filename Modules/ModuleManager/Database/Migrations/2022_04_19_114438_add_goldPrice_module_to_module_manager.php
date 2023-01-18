<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\ModuleManager\Entities\Module;

class AddGoldPriceModuleToModuleManager extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('modules')){
            Module::create([
                'name' => 'GoldPrice',
                'status' => 1,
                'order' => 9
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('modules')){
            $otp = Module::where('name', 'GoldPrice')->first();
            if($otp){
                $otp->delete();
            }
        }
    }
}
