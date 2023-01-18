<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FrontendCMS\Entities\HomePageCustomSection;
use Modules\FrontendCMS\Entities\HomePageSection;

class CreateHomePageCustomSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_page_custom_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->string('field_1')->nullable();
            $table->string('field_2')->nullable();
            $table->string('field_3')->nullable();
            $table->string('field_4', 500)->nullable();
            $table->string('field_5', 500)->nullable();
            $table->string('field_6', 500)->nullable();
            $table->timestamps();
        });
        $sections = HomePageSection::whereIn('section_name',['filter_category','discount_banner'])->get();
        foreach($sections as $section){
            HomePageCustomSection::create([
                'section_id' => $section->id
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
        Schema::dropIfExists('home_page_custom_sections');
    }
}
