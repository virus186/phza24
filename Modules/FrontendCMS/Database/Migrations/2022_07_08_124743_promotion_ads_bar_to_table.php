<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\FrontendCMS\Entities\DynamicPage;
use Modules\FrontendCMS\Entities\SubscribeContent;
use Modules\RolePermission\Entities\Permission;

class PromotionAdsBarToTable extends Migration
{
    
    public function up()
    {
        if(Schema::hasTable('subscribe_contents')){
            $exsist = SubscribeContent::whereIn('id', [3,4])->get();
            if(!$exsist->count()){
                $sql = [
                    ['id' => 3, 'title' => 'promotion bar', 'status' => 0, 'created_at' => now(),'updated_at' => now()],
                    ['id' => 4, 'title' => 'ads bar', 'status' => 0, 'created_at' => now(),'updated_at' => now()]
                ];
                SubscribeContent::insert($sql);
            }
        }

        if(Schema::hasTable('permissions')){
            $sql = [
                ['id'  => 717, 'module_id' => 3, 'parent_id' => 26, 'name' => 'Promotion bar', 'route' => 'frontendcms.promotionbar.index', 'type' => 2 ],
                ['id'  => 718, 'module_id' => 3, 'parent_id' => 717, 'name' => 'Update', 'route' => 'frontendcms.promotionbar.update', 'type' => 3 ],
                ['id'  => 719, 'module_id' => 3, 'parent_id' => 26, 'name' => 'Ads bar', 'route' => 'frontendcms.ads_bar.index', 'type' => 2 ],
                ['id'  => 720, 'module_id' => 3, 'parent_id' => 719, 'name' => 'Update', 'route' => 'frontendcms.ads_bar.update', 'type' => 3 ],
            ];
            DB::table('permissions')->insert($sql);
        }


        if(Schema::hasTable('dynamic_pages')){
            $about_us = DynamicPage::where('slug', 'about-us')->first();
            if($about_us){
                $about_us->update([
                    'is_static' => 0,
                    'is_page_builder' => 1
                ]);
            }
        }
    }

    
    public function down()
    {
        if(Schema::hasTable('permissions')){
            Permission::destroy([717,718,719,720]);
        }
    }
}
