<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Menu\Entities\MenuElement;

class CreateMegaMenuAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mega_menu_ads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->string('title', 255)->nullable();
            $table->string('subtitle', 255)->nullable();
            $table->string('link', 500)->nullable();
            $table->string('image', 255)->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        if(Schema::hasTable('menu_elements')){
            $element = MenuElement::where('type', 'function')->where('element_id', 1)->first();
            if(!$element){
                MenuElement::create([
                    'menu_id' => 1,
                    'column_id' => null,
                    'type' => 'function', 
                    'element_id' => 1, 
                    'title' => 'Lang & Currency', 
                    'link' => null, 
                    'parent_id' => null, 
                    'position' => 1
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
        Schema::dropIfExists('mega_menu_ads');
    }
}
