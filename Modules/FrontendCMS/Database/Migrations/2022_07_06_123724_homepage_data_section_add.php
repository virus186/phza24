<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FrontendCMS\Entities\HomePageSection;

class HomepageDataSectionAdd extends Migration
{
    
    public function up()
    {
        if(Schema::hasTable('home_page_sections')){
            if(!Schema::hasColumn('home_page_sections', 'theme')){
                Schema::table('home_page_sections', function (Blueprint $table) {
                    $table->string('theme')->default('default')->after('status');
                });
            }

            $sections = HomePageSection::all();
            foreach($sections as $key => $section){
                $section->update([
                    'theme' => 'default, amazy'
                ]);
            }

            $sql = [
                ['title' => 'House Appliances','section_name' => 'filter_category','section_for' => 4, 'column_size' => 'col-lg-12','type' => 1, 'status' => 1,'theme' => 'amazy','created_at' => now(),'updated_at' => now()],
                ['title' => 'Top Rating','section_name' => 'top_rating','section_for' => 1, 'column_size' => 'col-lg-12','type' => 5, 'status' => 1,'theme' => 'amazy', 'created_at' => now(),'updated_at' => now()],
                ['title' => 'People Choices','section_name' => 'people_choices','section_for' => 1, 'column_size' => 'col-lg-12','type' => 3, 'status' => 1,'theme' => 'amazy', 'created_at' => now(),'updated_at' => now()],
                ['title' => 'Discount Banner','section_name' => 'discount_banner','section_for' => 4, 'column_size' => 'col-lg-12','type' => 1, 'status' => 1,'theme' => 'amazy', 'created_at' => now(),'updated_at' => now()]
            ];

            HomePageSection::insert($sql);
        }
    }

    
    public function down()
    {
        $sections = HomePageSection::whereIn('section_name', ['filter_category','top_rating','people_choices','max_sale','discount_banner'])->pluck('id')->toArray();
        HomePageSection::destroy($sections);
    }
}
